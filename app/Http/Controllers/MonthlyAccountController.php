<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonthlyAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class MonthlyAccountController extends Controller
{
    // Mostrar el historial de contabilidades.
    public function index(Request $request): View
    {
        $query = MonthlyAccount::query();

        // Filtro por año.
        if ($request->filled('year')) {
            $query->where('year', $request->integer('year'));
        }

        // Filtro por mes.
        if ($request->filled('month')) {
            $query->where('month', $request->integer('month'));
        }

        $monthlyAccounts = $query
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->paginate(12)
            ->withQueryString();

        return view('monthly-accounts.index', compact('monthlyAccounts'));
    }

    // Mostrar el formulario para crear una contabilidad.
    public function create(): View
    {
        return view('monthly-accounts.create');
    }

    // Guardar una nueva contabilidad.
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'year' => ['required', 'integer', 'between:2000,2100'],
            'month' => ['required', 'integer', 'between:1,12'],
            'income' => ['required', 'numeric', 'min:0'],
            'period_start' => ['nullable', 'date'],
            'period_end' => [
                'nullable',
                'date',
                'after_or_equal:period_start',
            ],
            'notes' => ['nullable', 'string'],
        ]);

        // Evitar dos contabilidades para el mismo mes.
        $exists = MonthlyAccount::where('year', $data['year'])
            ->where('month', $data['month'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'month' => 'Ya existe una contabilidad para este mes y año.',
                ]);
        }

        $monthlyAccount = MonthlyAccount::create($data);

        return redirect()
            ->route('monthly-accounts.show', $monthlyAccount)
            ->with('success', 'Contabilidad creada correctamente.');
    }

    // Mostrar el documento mensual.
    public function show(MonthlyAccount $monthlyAccount): View
    {
        $monthlyAccount->load([
            'expenseItems.category',
            'expenseItems.expense',
        ]);

        return view(
            'monthly-accounts.show',
            compact('monthlyAccount')
        );
    }

    // Mostrar el formulario de edición.
    public function edit(MonthlyAccount $monthlyAccount): View
    {
        return view(
            'monthly-accounts.edit',
            compact('monthlyAccount')
        );
    }

    // Actualizar una contabilidad.
    public function update(
        Request $request,
        MonthlyAccount $monthlyAccount
    ): RedirectResponse {
        $data = $request->validate([
            'year' => ['required', 'integer', 'between:2000,2100'],
            'month' => ['required', 'integer', 'between:1,12'],
            'income' => ['required', 'numeric', 'min:0'],
            'period_start' => ['nullable', 'date'],
            'period_end' => [
                'nullable',
                'date',
                'after_or_equal:period_start',
            ],
            'notes' => ['nullable', 'string'],
        ]);

        // Evitar duplicados, excluyendo el registro actual.
        $exists = MonthlyAccount::where('year', $data['year'])
            ->where('month', $data['month'])
            ->where('id', '!=', $monthlyAccount->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'month' => 'Ya existe una contabilidad para este mes y año.',
                ]);
        }

        $monthlyAccount->update($data);

        return redirect()
            ->route('monthly-accounts.show', $monthlyAccount)
            ->with('success', 'Contabilidad actualizada correctamente.');
    }

    // Eliminar una contabilidad mensual.
    public function destroy(
        MonthlyAccount $monthlyAccount
    ): RedirectResponse {
        $monthlyAccount->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Contabilidad eliminada correctamente.');
    }
}
