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
            'title' => ['required']
        ]);

        Card::create([
            'title' => request('title'),
            'user_id' => auth()->id(),
            'started_at' => Carbon::createFromFormat('Y-m-d', $request->date),
            'ended_at' => Carbon::createFromFormat('Y-m-d', $request->date)->addDay(1),
            'color' => $request->color === 'bg-white' ? null : $request->color
        ]);
    }
}
