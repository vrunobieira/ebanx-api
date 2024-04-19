<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::prefix('account')->controller(AccountController::class)->group(function () {
    Route::post('/create', 'create');
    Route::get('/{id}/balance', 'balance');
    Route::post('/{id}/withdraw', 'withdraw');
    Route::post('/{id}/transfer', 'transfer');
    Route::post('/{id}/deposit', 'deposit');
    Route::post('/reset', 'reset');
});
