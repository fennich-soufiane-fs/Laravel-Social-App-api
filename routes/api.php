<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/send-mail', [AuthController::class, 'testMail']);
    Route::post('/forget-password-request', [AuthController::class, 'forgetPasswordRequest']);
    Route::post('/forget-password', [AuthController::class, 'verifyAndChangePassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/get-profile', [AuthController::class, 'getProfile']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'user'], function () {
        Route::apiResource('posts', PostController::class);
    });
});
Route::get('posts-public', [PostController::class, 'publicPosts']);
