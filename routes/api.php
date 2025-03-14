<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\FileMediaController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\SocialiteController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');
Route::apiResource('users', AdminController::class)->middleware('auth:sanctum');


Route::get('/videos', [FileMediaController::class, 'index']);
Route::post('/video/upload', [FileMediaController::class, 'store']);
Route::get('/video/show/{id}', [FileMediaController::class, 'show']);
Route::delete('/video/delete/{id}', [FileMediaController::class, 'destroy']);




Route::middleware(['api', 'web'])->group(function () {


    Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle']);
    Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);


    Route::get('auth/facebook', [SocialiteController::class, 'redirectToFacebook']);
    Route::get('auth/facebook/callback', [SocialiteController::class, 'handleFacebookCallback']);

});
