<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;


Route::post('/account/create', [AccountController::class, 'create']);

Route::get('/account/{id}/balance', [AccountController::class, 'balance']);

Route::post('/account/{id}/withdraw', [AccountController::class, 'withdraw']);

Route::post('/account/{id}/transfer', [AccountController::class, 'transfer']);

Route::post('/account/{id}/deposit', [AccountController::class, 'deposit']);