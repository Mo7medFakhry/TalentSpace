<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Profile::all();
        return response()->json($profiles);
    }

    public function show($id)
    {
        $profile = Profile::where('user_id', $id)->firstOrFail();
        return response()->json([$profile, 200]);
    }
    public function store(StoreProfileRequest $request)
    {
        $profile = Profile::create($request->validated());
        return response()->json([
            'message' => 'Profile Created Successfully',
            'profile' => $profile,
        ], 201);
    }

    public function update(UpdateProfileRequest $request, $id)
    {
        $profileu = Profile::findOrFail($id);
        $profileu->update($request->validated());
        return response()->json($profileu, 201);
    }
}
