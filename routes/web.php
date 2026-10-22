<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonthlyAccountController;
use App\Http\Controllers\ExpenseController;


// Página principal de PocketTrack.
Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| REGISTROS MENSUALES
|--------------------------------------------------------------------------
*/

// Mostrar formulario para crear un registro mensual.
Route::get('/registros/crear', [MonthlyAccountController::class, 'create'])->name('monthly-accounts.create');

// Guardar un nuevo registro mensual.
Route::post('/registros', [MonthlyAccountController::class, 'store'])->name('monthly-accounts.store');

// Mostrar un registro mensual completo.
Route::get('/registros/{monthlyAccount}', [MonthlyAccountController::class, 'show'])->whereNumber('monthlyAccount')->name('monthly-accounts.show');

// Mostrar formulario de edición de un registro mensual.
// Se conserva mientras implementamos el nuevo modal.
Route::get('/registros/{monthlyAccount}/editar', [MonthlyAccountController::class, 'edit'])->whereNumber('monthlyAccount')->name('monthly-accounts.edit');

// Actualizar los datos generales de un registro mensual.
// Esta ruta será utilizada por el nuevo modal de edición.
Route::put('/registros/{monthlyAccount}', [MonthlyAccountController::class, 'update'])->whereNumber('monthlyAccount')->name('monthly-accounts.update');

// Eliminar un registro mensual completo.
// IMPORTANTE: No utilizar esta ruta para eliminar gastos individuales.
Route::delete('/registros/{monthlyAccount}', [MonthlyAccountController::class, 'destroy'])->whereNumber('monthlyAccount')->name('monthly-accounts.destroy');

/*
|--------------------------------------------------------------------------
| GASTOS DE LOS REGISTROS MENSUALES
|--------------------------------------------------------------------------
*/
// Agregar un gasto a un registro mensual existente.
Route::post('/registros/{monthlyAccount}/gastos', [ExpenseController::class, 'storeMonthly'])->whereNumber('monthlyAccount')->name('monthly-accounts.expenses.store');

// Actualizar únicamente un gasto individual.
Route::put('/registros/{monthlyAccount}/gastos/{monthlyExpenseItem}',[ExpenseController::class, 'updateMonthly'])
    ->whereNumber('monthlyAccount')->whereNumber('monthlyExpenseItem')->name('monthly-expenses.update');

// Eliminar únicamente un gasto individual.
// No elimina el registro mensual al que pertenece.
Route::delete('/registros/{monthlyAccount}/gastos/{monthlyExpenseItem}',[ExpenseController::class, 'destroyMonthly'])
    ->whereNumber('monthlyAccount')->whereNumber('monthlyExpenseItem')->name('monthly-expenses.destroy');

/*
|--------------------------------------------------------------------------
| CATÁLOGO DE GASTOS
|--------------------------------------------------------------------------
| Administración de gastos reutilizables y permanentes.
| Esta sección es independiente de los registros mensuales.
|--------------------------------------------------------------------------
*/

Route::resource('expenses', ExpenseController::class);