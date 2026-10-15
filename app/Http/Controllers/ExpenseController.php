<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\MonthlyAccount;
use App\Models\MonthlyExpenseItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    /**
     * Mostrar el catálogo de gastos.
     */
    public function index(): View
    {
        $expenses = Expense::with('category')
            ->orderBy('name')
            ->paginate(15);

        return view('expenses.index', compact('expenses'));
    }

    /**
     * Mostrar el formulario de creación del catálogo.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('expenses.create', compact('categories'));
    }

    /**
     * Guardar una nueva definición en el catálogo.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_permanent' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
        ]);

        Expense::create($data);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Gasto creado correctamente.');
    }

    /**
     * Mostrar un gasto específico del catálogo.
     */
    public function show(Expense $expense): View
    {
        $expense->load('category');

        return view('expenses.show', compact('expense'));
    }

    /**
     * Mostrar el formulario de edición.
     */
    public function edit(Expense $expense): View
    {
        $categories = Category::orderBy('name')->get();

        return view(
            'expenses.edit',
            compact('expense', 'categories')
        );
    }

    /**
     * Actualizar un gasto del catálogo.
     */
    public function update(
        Request $request,
        Expense $expense
    ): RedirectResponse {

        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_permanent' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
        ]);

        $expense->update($data);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Gasto actualizado correctamente.');
    }

    /**
     * Eliminar un gasto del catálogo.
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Gasto eliminado correctamente.');
    }

    /**
     * NUEVA FUNCIÓN:
     * Registrar un gasto dentro de una contabilidad mensual.
     */
    public function storeMonthly(
        Request $request,
        MonthlyAccount $monthlyAccount
    ): RedirectResponse {

        // 1. Validar los datos del formulario.
        $data = $request->validate([
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id'),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'expense_date' => [
                'nullable',
                'date',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'is_permanent' => [
                'nullable',
                'boolean',
            ],
        ]);

        // 2. Determinar si el gasto será permanente.
        $isPermanent = $request->boolean('is_permanent');

        // 3. Ejecutar el guardado como una transacción.
        DB::transaction(function () use (
            $data,
            $monthlyAccount,
            $isPermanent
        ) {

            $expenseId = null;

            // 4. Si es permanente, crear una definición
            // reutilizable dentro del catálogo de gastos.
            if ($isPermanent) {

                $expense = Expense::create([
                    'category_id' => $data['category_id'],
                    'name' => $data['name'],
                    'description' => $data['description'] ?? null,
                    'is_permanent' => true,
                    'is_active' => true,
                ]);

                $expenseId = $expense->id;
            }

            // 5. Guardar el movimiento del mes.
            MonthlyExpenseItem::create([
                'monthly_account_id' => $monthlyAccount->id,
                'expense_id' => $expenseId,
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'amount' => $data['amount'],
                'expense_date' => $data['expense_date'] ?? null,
                'description' => $data['description'] ?? null,
            ]);

        });

        // 6. Volver al mismo documento mensual.
        return redirect()
            ->route('monthly-accounts.show', $monthlyAccount)
            ->with(
                'success',
                'Gasto agregado correctamente al registro mensual.'
            );
    }
}
