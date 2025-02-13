<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();

    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {

            $user = Socialite::driver('google')->user();
            $findUser = User::where('social_id', $user->id)->first();
            if ($findUser) {
                Auth::login($findUser);
                return response()->json([
                    'Message' => 'User Login Successfully',
                    'User' => $findUser,
                ], 201);
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'social_type' => 'google',
                    'password' => Hash::make('my-google')
                ]);
                Auth::login($newUser);


                return response()->json([
                    'Message' => 'User Login Successfully',
                    'User' => $newUser,
                ], 201);
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }


    public function handleFacebookCallback()
    {
        try {
        $socialUser = Socialite::driver('facebook')->user();

        // Check if email is provided by the social provider
        $email = $socialUser->getEmail() ?? null;

        // If email is not provided, generate a placeholder or handle accordingly
        if (!$email) {
            $email = "user_" . Str::random(4) . "@gmail.com"; // Placeholder email
        }

        // Create or update the user in the database
        $user = User::updateOrCreate(
            ['social_id' => $socialUser->getId(), 'social_type' => 'facebook'],
            [
                'name' => $socialUser->getName(),
                'email' => $email,
                'password' => Hash::make('my-facebook'), // Generate a random password
            ]
        );

        // Log the user in or return a response
        Auth::login($user);
        return response()->json(['message' => 'Login successful'], 200);

    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }

}

}


