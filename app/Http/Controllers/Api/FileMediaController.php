<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FileMedia;
use Illuminate\Http\Request;

class FileMediaController extends Controller
{

    public function index()
    {
        $videos = FileMedia::all();
        return response()->json($videos);
    }

    // Store a newly created resource in storage.

    public function store(Request $request)
    {
        $request->validate([
            'talent_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'video' => 'required|string',
            'tags' => 'nullable|string',
            'Status' => 'required|in:approved,pending,rejected',
        ]);

        $fileMedia = FileMedia::create([
            'talent_id' => $request->talent_id,
            'title' => $request->title,
            'description' => $request->description,
            'video' => $request->video,
            'tags' => $request->tags,
            'Status' => $request->Status,
        ]);

        return response()->json($fileMedia, 201);
    }

    // Update the specified resource in storage.

    public function update(Request $request, $id)
    {
        $request->validate([
            'talent_id' => 'sometimes|exists:users,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'video' => 'sometimes|string',
            'tags' => 'nullable|string',
            'Status' => 'sometimes|in:approved,pending,rejected',
        ]);

        $fileMedia = FileMedia::findOrFail($id);

        $fileMedia->update([
            'talent_id' => $request->talent_id ?? $fileMedia->talent_id,
            'title' => $request->title ?? $fileMedia->title,
            'description' => $request->description ?? $fileMedia->description,
            'video' => $request->video ?? $fileMedia->video,
            'tags' => $request->tags ?? $fileMedia->tags,
            'Status' => $request->Status ?? $fileMedia->Status,
        ]);

        return response()->json($fileMedia, 200);
    }

    /**
     * Retrieve video metadata
     */

    public function show($id)
    {
        $fileMedia = FileMedia::findOrFail($id);
        return response()->json($fileMedia);
    }

    /**
     * Delete a video
     */

    public function destroy($id)
    {
        $fileMedia = FileMedia::findOrFail($id);
        $fileMedia->delete();

        return response()->json(['message' => 'Video deleted successfully']);
    }
}
