<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CategoriesApiController;
use App\Http\Controllers\Api\ItemsApiController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('categories', [CategoriesApiController::class, 'index'])->name('categories.index');
Route::get('items', [ItemsApiController::class, 'index'])->name('items.index');
Route::get('items/{item:slug}', [ItemsApiController::class, 'show'])->name('items.show');
Route::post('items', [ItemsApiController::class, 'store'])->name('items.store');
Route::get('items/rating/all', [ItemsApiController::class, 'rating'])->name('items.rating');
Route::delete('items/{item}', [ItemsApiController::class, 'destroy'])->name('items.delete');

Route::middleware(['auth:sanctum', 'cors'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.api');
    Route::post('/transaction', [TransactionController::class, 'store'])->name('transaction.store');
    Route::put('/transaction/{transaction:reference}', [TransactionController::class, 'query'])->name('transactions.query');
    Route::get('/transaction/{transaction:reference}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
});

Route::group(['middleware' => ['cors', 'api']], function () {
    Route::get('/{driver}/redirect', [AuthController::class, 'redirect'])->name('api.redirect');
    Route::get('/{driver}/callback', [AuthController::class, 'callback'])->name('api.callback');
    Route::post('/login', [AuthController::class, 'login'])->name('login.api');
    Route::post('/register', [AuthController::class, 'register'])->name('register.api');
});
