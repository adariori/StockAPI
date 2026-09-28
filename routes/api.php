<?php

use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\MouvementStockController;
use App\Http\Controllers\Api\ProduitController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategorieController::class);
Route::apiResource('produits', ProduitController::class);

// route publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// route authentifié
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/mouvements', [MouvementStockController::class, 'index']);
    Route::get('/mouvements/{mouvement}', [MouvementStockController::class, 'show']);
    Route::post('/mouvements', [MouvementStockController::class, 'store']);

    Route::middleware('admin')->group(function () {
        Route::get('/admin-test', fn () => response()->json(['message' => 'Bienvenue admin']));
    });
});
