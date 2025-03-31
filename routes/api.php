<?php

use App\Http\Controllers\API\AuthController;
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
    });
});

