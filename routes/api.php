<?php

use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register' , [UserController::class , 'register']);
Route::post('/login' , [UserController::class , 'login']);
Route::post('/logout' , [UserController::class , 'logout'])->middleware('auth:sanctum');
Route::apiResource('users' , UserController::class)->middleware('auth:sanctum');


Route::prefix('profile')->group(function () {
    Route::post('', [ProfileController::class, 'store']);
    Route::get('/{id}', [ProfileController::class, 'show']);
    Route::put('/{id}', [ProfileController::class, 'update']);

});

Route::get('user/{id}/profile', [UserController::class, 'getprofile']);
