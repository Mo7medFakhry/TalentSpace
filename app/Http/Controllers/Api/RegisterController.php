<?php

namespace App\Http\Controllers\Api;

use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

    public function register(RegisterUserRequest $request)
    {
        $user = User::create($request->validated());

        event(new Registered($user));

        $device = substr($request->userAgent() ?? '', 0, 255);
        
        return response()->json([
            'Message' => 'User Registered Successfully',
            'User' => $user ,
            'access_token' => $user->createToken($device)->plainTextToken,
            201
        ]);
    }

}
