<?php

namespace App\Http\Controllers;

use App\Models\GoogleAccount;
use App\Services\Google;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $accounts = GoogleAccount::with('calendars')->where('user_id', auth()->user()->id)->first();

        return Inertia::render('Settings/Edit', [
            'accounts' => [
                'name' => $accounts?->name,
                'calendars' => $accounts?->calendars->map(function ($calendar) {
                    return [
                        'id' => $calendar->id,
                        'name' => $calendar->name,
                        'color' => $calendar->color,
                        'import' => $calendar->import,
                    ];
                }),
                'selectedCalendars' => $accounts?->calendars->where('import', true)->pluck('id') ?? [],
            ],
        ]);
    }

    public function store(Request $request, Google $google)
    {
        if (! $request->has('code')) {
            return redirect($google->createAuthUrl());
        }

        $google->authenticate($request->get('code'));

        $account = $google->service('Oauth2')->userinfo->get();

        auth()->user()->googleAccounts()->updateOrCreate(
            [
                'google_id' => $account->id,
            ],
            [
                'name' => $account->email,
                'token' => $google->getAccessToken(),
            ]
        );

        return redirect()->route('settings.edit');
    }

    public function update(Request $request, Google $google)
    {
        $request->validate([
            'selectedCalendars' => 'array',
        ]);

        $this->getGoogleService();

        $googleAccount = $request->user()->googleAccounts()->first();

        $calendarsWhereIn = $googleAccount->calendars()->whereIn('id', $request->selectedCalendars)->get();

        $calendarsWhereNotIn = $googleAccount->calendars()->whereNotIn('id', $request->selectedCalendars)->get();

        foreach ($calendarsWhereIn as $calendar) {
            $calendar->update(['import' => true]);
        }

        foreach ($calendarsWhereNotIn as $calendar) {
            $calendar->update(['import' => false]);
        }

        return redirect()->route('settings.edit');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        auth()->user()->googleAccounts()->delete();

        return redirect()->route('settings.edit');
    }

    public function getGoogleService()
    {
        $token = request()->user()->googleAccounts->value('token');

        return app(Google::class)
            ->connectUsing($token)
            ->service('Calendar');
    }
}
