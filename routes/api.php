<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ControllerCauldron;

// Public authentication routes
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Store route (needs token)
    Route::get('/store/items', [StoreController::class, 'getItems']);

    // Inventory routes
    Route::get('/inventory', [InventoryController::class, 'getInventory']);
    Route::get('/wallet/balance', [InventoryController::class, 'getBalance']);

    // Shop routes
    Route::post('/shop/buy', [ShopController::class, 'buyItem']);
    Route::post('/shop/sell', [ShopController::class, 'sellItem']);

    // cauldron routes
    Route::post('/cauldron/brew', [ControllerCauldron::class, 'brewPotion']);
});