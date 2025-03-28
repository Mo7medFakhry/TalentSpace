<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FileMedia;
use Illuminate\Http\Request;

class FileMediaController extends Controller
{

    public function index()
    {
        $videos = FileMedia::with('talent:id,name,profile_picture')->get();
        return response()->json($videos);
    }

    // Store a newly created resource in storage.

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'talent_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'video' => 'required|string',
            'tags' => 'required|string',
            'date' => 'required|date',
            'city' => 'required|string',
            'thumbnail' => 'required|string',
        ]);

        $fileMedia = FileMedia::create($validatedData);

        return response()->json([
            'message' => 'Video successfully created.',
            'data' => $fileMedia
        ], 201);
    }

    // Update the specified resource in storage.

    public function update(Request $request, $id)
    {
        // Validate the request data with optional fields
        $validatedData = $request->validate([
            'talent_id' => 'sometimes|exists:users,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'video' => 'sometimes|string',
            'tags' => 'sometimes|string',
            'date' => 'sometimes|date',
            'city' => 'sometimes|string',
            'thumbnail' => 'sometimes|string',
        ]);

        // Find the FileMedia record or fail with a 404 response
        $fileMedia = FileMedia::findOrFail($id);

        // Update only the provided fields
        $fileMedia->update($validatedData);

        return response()->json([
            'message' => 'Video  successfully updated.',
            'data' => $fileMedia
        ], 200);
    }

    /**
     * Retrieve video metadata
     */

    public function show($id)
    {
        $fileMedia = FileMedia::with('talent:id,name,profilePicture')->findOrFail($id);
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
