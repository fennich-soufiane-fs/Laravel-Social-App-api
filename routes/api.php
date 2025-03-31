<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/register', [AuthController::class, 'register']);
Route::get('', function () {
    return response()->json(['message' => 'Test route working!']);
});
