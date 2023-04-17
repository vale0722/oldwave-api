<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CategoriesApiController;
use App\Http\Controllers\Api\ItemsApiController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::resource('items', ItemsApiController::class)
    ->parameters([
        'items' => 'item:slug',
    ])
    ->except('create', 'edit', 'update', 'destroy');
Route::delete('items/{item}', [ItemsApiController::class, 'destroy'])->name('items.delete');
Route::get('items/rating/all', [ItemsApiController::class, 'rating'])
    ->name('items.rating');

Route::get('categories', [CategoriesApiController::class, 'index'])
    ->name('categories.index');

Route::middleware(['auth:sanctum', 'cors'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout.api');
    Route::post('/transaction', [TransactionController::class, 'store'])
        ->name('transaction.store');
    Route::put('/transaction/{transaction:reference}', [TransactionController::class, 'query'])
        ->name('transactions.query');
    Route::get('/transaction/{transaction:reference}', [TransactionController::class, 'show'])
        ->name('transactions.show');
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');
});

Route::group(['middleware' => ['cors', 'api']], function () {
    Route::get('/{driver}/redirect', [AuthController::class, 'redirect'])
        ->name('api.redirect');
    Route::get('/{driver}/callback', [AuthController::class, 'callback'])
        ->name('api.callback');
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.api');
    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.api');
});
