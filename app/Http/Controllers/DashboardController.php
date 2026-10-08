<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonthlyAccount;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
     public function index(): View
    {
        // Recuperar los últimos 12 registros mensuales.
        $monthlyAccounts = MonthlyAccount::query()
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->limit(12)
            ->get();

        // Enviar los registros a la vista del Dashboard.
        return view('dashboard.index', [
            'monthlyAccounts' => $monthlyAccounts,
        ]);
    }
}
