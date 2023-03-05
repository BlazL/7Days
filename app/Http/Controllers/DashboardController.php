<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
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


        $week = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $week[] = [
                'day' => $date->translatedFormat('j. n.'),
                'current' => $date->isToday(),
                'weekDay' => $date->translatedFormat('l'),
                'unFormattedDate' => $date->format('Y-m-d')
            ];
        }

        $cards = Card::whereBetween('started_at', [$startDate, $endDate])->get()->groupBy(function ($item) {
            return $item->started_at->format('Y-m-d');
        });


        return Inertia::render('Dashboard', [
            'daysOfWeek' => $week,
            'currentDay' => Carbon::now()->format('Y-m-d'),
            'title' => $title,
            'cards' => $cards
        ]);
    }
}
