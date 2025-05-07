<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:100',
            'birthday' => 'nullable|date',
            'bio' => 'nullable|string',
            'profilePicture' => 'nullable|string',
            'gender' => 'required|in:Male,Female',
            'role' => 'required|in:Talent,Investor,Mentor',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }
        $user = User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'birthday' => $request->birthday,
            'bio' => $request->bio,
            'profilePicture' => $request->profilePicture,
            'gender' => $request->gender,
            'role' => $request->role,
        ]);

        if ($user) {
            return response()->json([
                'Message' => 'User Created Successfully',
                'User' => $user
                ,
                201
            ]);
        }
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

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted'], 200);
    }


}
