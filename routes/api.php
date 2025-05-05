<?php

use App\Http\Controllers\Api\AchievementController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\FileMediaController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SocialiteController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\API\FollowController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//----------------Auth----------------
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    //----------------Users----------------
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::apiResource('users', AdminController::class);


    //----------------Videos----------------
    Route::get('/videos', [FileMediaController::class, 'index']);
    Route::post('/video/upload', [FileMediaController::class, 'store']);
    Route::put('/video/update/{id}', [FileMediaController::class, 'update']);
    Route::get('/video/show/{id}', [FileMediaController::class, 'show']);
    Route::delete('/video/delete/{id}', [FileMediaController::class, 'destroy']);

    // ----------------Followers----------------
    Route::post('/follow/{user}', [FollowController::class, 'follow']);
    Route::post('/unfollow/{user}', [FollowController::class, 'unfollow']);
    Route::get('/followers/{user}', [FollowController::class, 'followers']);
    Route::get('/following/{user}', [FollowController::class, 'following']);



    // ----------------Reviews----------------
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/users/{id}/reviews', [ReviewController::class, 'showReviews']);



    // ----------------Achievements----------------
    Route::apiResource('achievements', AchievementController::class);


    // ----------------Offers----------------
    Route::prefix('offers')->group(function () {
        // Routes for all authenticated users
        Route::get('/', [OfferController::class, 'index']);
        Route::get('/{offer}', [OfferController::class, 'show']);

        // Investor routes
        Route::post('/', [OfferController::class, 'store'])->middleware('role:investor');

        // Admin routes
        Route::patch('/{offer}/admin-review', [OfferController::class, 'adminReview'])->middleware('role:admin');

        // Talent routes
        Route::patch('/{offer}/talent-review', [OfferController::class, 'talentReview'])->middleware('role:talent');
    });
});



//------------------Socialite----------------
Route::middleware(['api', 'web'])->group(function () {


    Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle']);
    Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);


    Route::get('auth/facebook', [SocialiteController::class, 'redirectToFacebook']);
    Route::get('auth/facebook/callback', [SocialiteController::class, 'handleFacebookCallback']);

});
