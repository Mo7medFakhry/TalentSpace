<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password')))
            return response()->json(
                [
                    'Message' => 'invalid email or password'
                ],
                401
            );
        $user = User::where('email', $request->email)->FirstOrFail();
        $token = $user->createToken('token-name')->plainTextToken;

        return response()->json([
            'Message' => 'User Login Successfully',
            'User' => $user,
            'Token' => $token
        ], 201);
    }


}
