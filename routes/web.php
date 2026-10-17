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

// Mostrar formulario de edición.
Route::get('/registros/{monthlyAccount}/editar',
    [MonthlyAccountController::class, 'edit'])->name('monthly-accounts.edit');

// Guardar cambios de un registro mensual.
Route::put('/registros/{monthlyAccount}',
    [MonthlyAccountController::class, 'update'])->name('monthly-accounts.update');

// Eliminar un registro mensual.
Route::delete('/registros/{monthlyAccount}',
    [MonthlyAccountController::class, 'destroy'])->name('monthly-accounts.destroy');

// Editar un registro de gasto
Route::put(
    '/registros/{monthlyAccount}/gastos/{monthlyExpenseItem}',[ExpenseController::class, 'updateMonthly']
)->name('monthly-expenses.update');

// Eliminar únicamente un gasto de un registro mensual.
Route::delete(
    '/registros/{monthlyAccount}/gastos/{monthlyExpenseItem}',
    [ExpenseController::class, 'destroyMonthly'])->whereNumber('monthlyAccount')->whereNumber('monthlyExpenseItem')->name('monthly-expenses.destroy');