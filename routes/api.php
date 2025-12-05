<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlotController;
use App\Http\Controllers\GameStateController;

// Autenticación
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Plots
    Route::get('/plots', [PlotController::class, 'index']);
    Route::post('/plots/{id}/plant', [PlotController::class, 'plant']);
    Route::post('/plots/{id}/water', [PlotController::class, 'water']);
    Route::post('/plots/{id}/fertilize', [PlotController::class, 'fertilize']);
    Route::delete('/plots/{id}/harvest', [PlotController::class, 'harvest']);
    Route::post('/plots/{id}/remove', [PlotController::class, 'remove']);

    // Game State
    Route::get('/game/state', [GameStateController::class, 'state']);
});
