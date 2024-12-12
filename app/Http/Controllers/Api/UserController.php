<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json([
            'Message' => 'User Registered Successfully',
            'User' => $user
            ,
            201
        ]);
    }

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


    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }


    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->update($request->validated());

        return response()->json([
            'Message' => 'User Updated Successfully',
            'User' => $user
            ,
            201
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'Message' => 'Logout Successfully'
        ]);
    }


    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted'], 200);
    }


    public function getprofile($id)
    {
        $profile = User::find($id)->profile;
        return response()->json($profile, 200);
    }

}
