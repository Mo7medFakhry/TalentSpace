<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{

    public function index()
    {
        $achievements = Achievement::all();
        return response()->json($achievements);
    }

    public function store(Request $request)
    {
        $request->validate([
            'talent_id' => 'required|exists:users,id',
            'decision' => 'in:approved,pending,rejected',
            'certification' => 'nullable|string',
            'reviewMentor' => 'nullable|string',
            'Type' => 'required|string',
        ]);

        $achievement = Achievement::create($request->all());

        return response()->json([
            'Message' => 'Achievement Successfully created.',
            'data' => $achievement
        ], 201);
    }

    public function show($id)
    {
        $achievement = Achievement::find($id);

        if (!$achievement) {
            return response()->json(['message' => 'achievement not found'], 404);
        }
        return response()->json($achievement);
    }

    public function update(Request $request, $id)
    {

        $ValidationData = $request->validate([
            'decision' => 'in:approved,pending,rejected',
            'certification' => 'nullable|string',
            'reviewMentor' => 'nullable|string',
            'Type' => 'required|string',
        ]);

        $achievement = Achievement::findOrFail($id);

        $achievement->update($ValidationData);


        return response()->json([
            'Message' => 'Achievement Successfully updated.',
            'data' => $achievement
        ], 200);
    }

    public function destroy($id)
    {
        $achievement = Achievement::find($id);
        if (!$achievement) {
            return response()->json(['message' => 'Achievement not found'], 404);
        }

        $achievement->delete();
        return response()->json(['message' => 'Achievement deleted'], 200);
    }
}
