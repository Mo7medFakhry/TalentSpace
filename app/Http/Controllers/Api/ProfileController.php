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
    /*
    /**
     * Show All Profiles
     */
    public function index()
    {
        $profiles = Profile::all();
        return response()->json($profiles);
    }

    /*
    /**
    * Show a EachOne Profile
    */
    public function show($id)
    {
        $profile = Profile::where('user_id', $id)->firstOrFail();
        return response()->json([$profile, 200]);
    }

    /*
    /**
    * Store a Profile
    */
    public function store(StoreProfileRequest $request)
    {
        $user = Auth::user();

        // Check if the user already has a profile
        if (Profile::where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'User already has a profile'
            ], 400);
        }

        // Create profile if none exists
        $profile = Profile::create(array_merge($request->validated(), ['user_id' => $user->id]));

        return response()->json([
            'message' => 'Profile Created Successfully',
            'profile' => $profile,
        ], 201);

        // $profile = Profile::create($request->validated());
        // return response()->json([
        //     'message' => 'Profile Created Successfully',
        //     'profile' => $profile,
        // ], 201);
    }

    /*
    /**
    * Update a Profile
    */
    public function update(UpdateProfileRequest $request, $id)
    {

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $profile = Profile::where('id', $id)->where('user_id', $user->id)->first();

        if (!$profile) {
            return response()->json(['message' => 'Profile not found or unauthorized'], 403);
        }

        $profile->update($request->validated());

        return response()->json(['message' => 'Profile Updated Successfully', 'profile' => $profile], 200);

        // $profileu = Profile::findOrFail($id);
        // $profileu->update($request->validated());
        // return response()->json($profileu, 201);
    }

    /*
    /**
    * Delete a Profile
    */
    public function destroy($id)
    {
        $profile = Profile::findOrFail($id);
        $profile->delete();

        return response()->json(['message' => 'Profile deleted successfully']);
    }


}
