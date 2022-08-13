<?php

use App\Http\Controllers\Api\CategoriesApiController;
use App\Http\Controllers\Api\ItemsApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('categories', [CategoriesApiController::class, 'index'])->name('categories.index');
Route::get('items', [ItemsApiController::class, 'index'])->name('items.index');
Route::get('items/{item:slug}', [ItemsApiController::class, 'show'])->name('items.show');
Route::post('items', [ItemsApiController::class, 'store'])->name('items.store');
Route::get('items/rating/all', [ItemsApiController::class, 'rating'])->name('items.rating');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
