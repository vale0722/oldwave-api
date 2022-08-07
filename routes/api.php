<?php

use App\Http\Controllers\Api\CategoriesApiController;
use App\Http\Controllers\Api\ImagesApiController;
use App\Http\Controllers\Api\ProductsApiController;
use App\Http\Controllers\Api\RatingProductsApiController;
use App\Http\Controllers\Api\SellersApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoriesApiController::class);
Route::apiResource('seller', SellersApiController::class);
Route::apiResource('product', ProductsApiController::class);
Route::apiResource('image', ImagesApiController::class);
Route::apiResource('ratingProduct', RatingProductsApiController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
