<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonthlyAccountController;


Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/registros/crear', [MonthlyAccountController::class, 'create'])
    ->name('monthly-accounts.create');