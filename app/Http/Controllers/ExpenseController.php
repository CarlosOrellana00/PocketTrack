<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    // Mostrar el catálogo de gastos.
    public function index(): View
    {
        $expenses = Expense::with('category')
            ->orderBy('name')
            ->paginate(15);

        return view('expenses.index', compact('expenses'));
    }

    // Mostrar el formulario de creación.
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('expenses.create', compact('categories'));
    }

    // Guardar un nuevo gasto en el catálogo.
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

    // Mostrar un gasto específico.
    public function show(Expense $expense): View
    {
        $expense->load('category');

        return view('expenses.show', compact('expense'));
    }

    // Mostrar el formulario de edición.
    public function edit(Expense $expense): View
    {
        $categories = Category::orderBy('name')->get();

        return view('expenses.edit', compact('expense', 'categories'));
    }

    // Actualizar un gasto.
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

    // Eliminar un gasto del catálogo.
    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Gasto eliminado correctamente.');
    }
}
