<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth.api'])->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);

    Route::controller(ExpenseController::class)->prefix('expenses')->group(function () {
        Route::get('/', 'list');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    Route::controller(InvoiceController::class)->prefix('invoices')->group(function () {
        Route::get('/', 'list');
    });
});
