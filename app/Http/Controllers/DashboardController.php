<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Card $card = null)
    {
        $startDate = Carbon::today()->startOfWeek();
        $endDate = Carbon::today()->endOfWeek();

        $monthNameStart = $startDate->translatedFormat('M Y');
        $monthNameEnd = $endDate->translatedFormat('M Y');

        if ($monthNameStart !== $monthNameEnd) {
            $title = $monthNameStart . ' - ' . $monthNameEnd;
        } else {
            $title = $monthNameStart;
        }

        $todos = Card::where('user_id', auth()->id())->whereBetween('started_at', [$startDate, $endDate])->orderBy('position')->get()->groupBy(function ($item) {
            return $item->started_at->format('Y-m-d');
        });

        $week = [];
        $cards = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $week[] = [
                'day' => $date->translatedFormat('j. n.'),
                'current' => $date->isToday(),
                'weekDay' => $date->translatedFormat('l'),
                'unFormattedDate' => $date->format('Y-m-d'),
            ];
            $cards[$date->format('Y-m-d')] = $todos[$date->format('Y-m-d')] ?? [];
        }

        return Inertia::render('Dashboard/Index', [
            'week' => $week,
            'currentDay' => Carbon::now()->format('Y-m-d'),
            'title' => $title,
            'cards' => $cards,
            'card' => $card
        ]);
    }
}
