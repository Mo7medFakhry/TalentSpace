<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{

    public function getprofile($id)
    {
        $profile = User::find($id)->profile;
        return response()->json($profile, 200);
    }

}
