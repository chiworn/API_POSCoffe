<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GlassController;
use App\Http\Controllers\GlassUseController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('products', ProductController::class);
    Route::post('products/{id}', [ProductController::class, 'update']); // Still need this for multipart/form-data with PUT issues

    Route::apiResource('images', ImageController::class);
    Route::post('images/{id}', [ImageController::class, 'update']);

    Route::apiResource('itemorders', ItemController::class);
    Route::apiResource('saller', SaleController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('glasses', GlassController::class);
    Route::apiResource('stocks', StockController::class);
    Route::apiResource('glass-uses', GlassUseController::class);
});
