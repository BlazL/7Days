<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CardController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date:y-d-m'],
            'title' => ['required'],
        ]);

        Card::create([
            'title' => request('title'),
            'user_id' => auth()->id(),
            'started_at' => Carbon::createFromFormat('Y-m-d', $request->date),
            'ended_at' => Carbon::createFromFormat('Y-m-d', $request->date)->addDay(1),
            'color' => $request->color === 'bg-white' ? null : $request->color,
        ]);

        return redirect()->back();
    }

    public function show()
    {
    }

    public function update(Request $request, Card $card)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $card->update(['title' => $request->title, 'description' => $request->description, 'color' => $request->color === 'bg-white' ? null : $request->color,]);

        if (request()->has('redirectUrl')) {
            return redirect(request('redirectUrl'));
        }

        return redirect()->back();
    }

    public function move(Request $request, Card $card)
    {
        $request->validate([
            'position' => "required|numeric",
            'day' => "required"
        ]);

        $card->update([
            'started_at' => Carbon::createFromFormat('Y-m-d', $request->day),
            'position' => round($request->position, 5)
        ]);

        return back();
    }

    public function destroy(Card $card)
    {
        $card->delete();
        return redirect()->route('dashboard');
    }
}
