<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonthlyAccountController;
use App\Http\Controllers\ExpenseController;


Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

//Registro Mensual

Route::get('/registros/crear', [MonthlyAccountController::class, 'create'])
    ->name('monthly-accounts.create');

Route::post('/registros', [MonthlyAccountController::class, 'store'])
    ->name('monthly-accounts.store');

Route::get('/registros/{monthlyAccount}', [MonthlyAccountController::class, 'show'])
    ->whereNumber('monthlyAccount')->name('monthly-accounts.show');

// Guardar un gasto dentro de un registro mensual
Route::post('/registros/{monthlyAccount}/gastos',[ExpenseController::class, 'storeMonthly'])
    ->whereNumber('monthlyAccount')->name('monthly-accounts.expenses.store');