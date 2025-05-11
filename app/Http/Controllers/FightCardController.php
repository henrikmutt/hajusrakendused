<?php

namespace App\Http\Controllers;
use App\Models\FightCard;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FightCardController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|url',
            'description' => 'required|string',
            'blue_corner' => 'required|string|max:255',
            'red_corner' => 'required|string|max:255',
        ]);

        FightCard::create([
            'title' => $request->title,
            'image' => $request->image,
            'description' => $request->description,
            'blue_corner' => $request->blue_corner,
            'red_corner' => $request->red_corner,
            'uuid' => '07b4cc44-9042-4944-b1dc-56eac757ca4f',
        ]);

        return redirect()->back()->with('success', 'Fight card added.');
    }

    public function create()
    {
        return Inertia::render('fightCards/Create');
    }

    public function apiByUuid($uuid)
    {
        $fightCards = \App\Models\FightCard::where('uuid', $uuid)->get();

        if ($fightCards->isEmpty()) {
            return response()->json(['error' => 'No fight cards found for this UUID.'], 404);
        }

        return response()->json($fightCards);
    }



}