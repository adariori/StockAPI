<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// route publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// route authentifié
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('admin')->group(function () {
        Route::get('/admin-test', fn () => response()->json(['message' => 'Bienvenue admin']));
    });
});
