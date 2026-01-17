<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashiersaleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GlassController;
use App\Http\Controllers\GlassUseController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\stocksController;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::middleware('auth:sanctum')->group(function(){
  Route::post('/test-upload', function (Request $request) {
    if (!$request->hasFile('image')) {
        return response()->json(['error' => 'គ្មាន image'], 400);
    }

    $cloudinary = new Cloudinary([
        'cloud' => [
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
            'api_key'    => env('CLOUDINARY_API_KEY'),
            'api_secret' => env('CLOUDINARY_API_SECRET'),
        ],
        'url' => [
            'secure' => true
        ]
    ]);

    $upload = $cloudinary->uploadApi()->upload(
        $request->file('image')->getRealPath(),
        [
            'folder' => 'products'
        ]
    );

    return response()->json([
        'url' => $upload['secure_url'],
        'public_id' => $upload['public_id']
    ]);
});
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/product',[ProductController::class,'index']);
    Route::post('/product',[ProductController::class,'store']);
    Route::post('/product/{id}',[ProductController::class,'update']);
    Route::delete('/product/{id}',[ProductController::class,'destroy']);
    //Route image
    Route::get('/image',[ImageController::class,'index']);
    Route::post('/image',[ImageController::class,'store']);
    Route::post('/image/{id}',[ImageController::class,'update']);
    Route::delete('/image/{id}',[ImageController::class,'destroy']);
    //Route Items
    Route::get('/itemorders',[ItemsController::class, 'index']);
    Route::post('/itemorders',[ItemsController::class, 'store']);
    Route::get('/itemorders/{id}',[ItemsController::class, 'show']);
    Route::put('/itemorders/{id}',[ItemsController::class, 'update']);
    Route::delete('/itemorders/{id}',[ItemsController::class, 'destroy']);
    //Route Salled
    Route::get('/saller',[CashiersaleController::class,'index']);
    Route::post('/saller',[CashiersaleController::class,'store']);
    Route::get('/saller/{id}',[CashiersaleController::class,'show']);
    Route::put('/saller/{id}',[CashiersaleController::class,'update']);
    Route::delete('/saller/{id}',[CashiersaleController::class,'destory']);
    //Route  Category
    Route::get('/categories', [CategoryController ::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    //Route Glass
    Route::get('glasses', [GlassController::class, 'index']);
    Route::post('glasses', [GlassController::class, 'store']);
    Route::get('glasses/{id}', [GlassController::class, 'show']);
    Route::put('glasses/{id}', [GlassController::class, 'update']);
    Route::delete('glasses/{id}', [GlassController::class, 'destroy']);
    //Stock Controlleer
    Route::get('stocks', [stocksController::class, 'index']);
    Route::post('stocks', [stocksController::class, 'store']);
    Route::put('stocks/{id}', [stocksController::class, 'update']);
    Route::delete('stocks/{id}', [stocksController::class, 'destroy']);
    //Glass_used
    Route::get('glass-uses', [GlassUseController::class, 'index']);
    Route::post('glass-uses', [GlassUseController::class, 'store']);
    Route::put('glass-uses/{id}', [GlassUseController::class, 'update']);
    Route::delete('glass-uses/{id}', [GlassUseController::class, 'destroy']);
    });
