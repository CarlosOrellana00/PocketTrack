
@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Mensaje de creación exitosa --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="ti ti-circle-check me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Cerrar">
            </button>
        </div>
    @endif

    {{-- Encabezado --}}
    <div class="mb-4">
        <h3 class="mb-1">
            <i class="ti ti-file-description me-2"></i>
            Registro mensual
        </h3>

        <p class="text-muted mb-0">
            Documento de contabilidad personal
        </p>
    </div>

    {{-- Datos principales --}}
    <div class="card mb-4">

        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-calendar-month me-2"></i>
                Datos del registro
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-3">

                
                {{-- Mes: solo lectura --}}
                <div class="col-md-6">
                    <strong>Mes:</strong>
                    <p>
                        {{ [
                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
                            4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
                            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
                            10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                        ][(int) $monthlyAccount->month] ?? 'Mes no definido' }}
                    </p>
                </div>

                {{-- Año: solo lectura --}}
                <div class="col-md-6">
                    <strong>Año:</strong>
                    <p>{{ $monthlyAccount->year }}</p>
                </div>

                {{-- Ingreso mensual: solo lectura --}}
                <div class="col-md-12">
                    <strong>Ingreso mensual (CLP):</strong>
                    <h4 class="text-success">
                        ${{ number_format($monthlyAccount->income, 0, ',', '.') }}
                    </h4>
                </div>

                <div class="col-md-6">
                    <strong>Inicio del periodo:</strong>
                    <p>
                        {{ $monthlyAccount->period_start?->format('d-m-Y') ?? 'No definido' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <strong>Fin del periodo:</strong>
                    <p>
                        {{ $monthlyAccount->period_end?->format('d-m-Y') ?? 'No definido' }}
                    </p>
                </div>

                <div class="col-md-12">
                    <strong>Observaciones:</strong>
                    <p>
                        {{ $monthlyAccount->notes ?: 'Sin observaciones' }}
                    </p>
                </div>

            </div>

        </div>
    </div>

    {{-- Gastos del registro --}}
    <div class="card mb-4">

        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-receipt me-2"></i>
                Gastos del registro
            </h5>
        </div>

        <div class="card-body">
            @php
                // Agrupar los gastos por categoría.
                $gastosPorCategoria = $monthlyAccount->expenseItems
                    ->groupBy('category_id');

                // Calcular el total general de gastos.
                $totalGastos = $monthlyAccount->expenseItems->sum('amount');
            @endphp
            @if ($monthlyAccount->expenseItems->isEmpty())
                <div class="text-center py-4">
                    <i class="ti ti-receipt-off fs-1 text-muted"></i>

                    <p class="text-muted mb-2">
                        Todavía no se han registrado gastos
                        en este documento.
                    </p>
                </div>
            @else
                <p class="text-muted mb-3">
                    Hay {{ $monthlyAccount->expenseItems->count() }}
                    gastos registrados.
                </p>
                @foreach ($gastosPorCategoria as $categoryId => $gastos)
                    @php
                        $categoria = $gastos->first()->category;
                        $subtotal = $gastos->sum('amount');
                    @endphp
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                {{ $categoria?->name ?? 'Sin categoría' }}
                            </h3>
                            <span class="fw-bold">
                                ${{ number_format($subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>Nombre del gasto</th>
                                        <th>Fecha</th>
                                        <th>Descripción</th>
                                        <th class="text-end">Monto</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gastos as $gasto)
                                        <tr>
                                            <td>{{ $gasto->name }}</td>

                                            <td>
                                                {{ $gasto->expense_date
                                                    ? \Carbon\Carbon::parse($gasto->expense_date)->format('d-m-Y')
                                                    : 'Sin fecha' }}
                                            </td>

                                            <td>
                                                {{ $gasto->description ?: 'Sin descripción' }}
                                            </td>

                                            <td class="text-end fw-semibold">
                                                ${{ number_format($gasto->amount, 0, ',', '.') }}
                                            </td>

                                            {{-- Acciones del gasto --}}
                                            <td class="text-end">
                                                <div class="d-inline-flex align-items-center gap-2">

                                                    {{-- Editar gasto --}}
                                                    <button
                                                        type="button"
                                                        class="btn btn-outline-info btn-icon"
                                                        title="Editar gasto"
                                                        aria-label="Editar gasto"
                                                        disabled
                                                    >
                                                        <i class="ti ti-edit"></i>
                                                    </button>

                                                    {{-- Eliminar gasto --}}
                                                    <button
                                                        type="button"
                                                        class="btn btn-outline-danger btn-icon"
                                                        title="Eliminar gasto"
                                                        aria-label="Eliminar gasto"
                                                        disabled
                                                    >
                                                        <i class="ti ti-trash"></i>
                                                    </button>

                                                </div>
                                            </td>
    
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                @endforeach

                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                    <h3 class="mb-0">
                        Total general de gastos
                    </h3>

                    <h2 class="mb-0 text-danger">
                        ${{ number_format($totalGastos, 0, ',', '.') }}
                    </h2>
                </div>

            @endif


        </div>
    </div>

    {{-- Sección de próximos desarrollos --}}

    
{{-- Formulario para agregar gastos --}}
<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ti ti-circle-plus me-2"></i>
            Agregar gasto al registro mensual
        </h3>
    </div>

    <div class="card-body">

            <p class="text-secondary mb-4">
                Registra un nuevo gasto en este documento mensual.
            </p>

            <form id="expenseForm" action="{{ route('monthly-accounts.expenses.store', ['monthlyAccount' => $monthlyAccount->id]) }}" method="POST">
            @csrf

                <div class="row g-3">

                    {{-- Categoría --}}
                    <div class="col-12">
                        <label for="category_id" class="form-label">
                            Tipo de gasto *
                        </label>

                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">Seleccionar categoría</option>
                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected(old('category_id') == $category->id)
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <small class="form-hint">
                            Selecciona la categoría del gasto.
                        </small>
                    </div>

                    {{-- Nombre --}}
                    <div class="col-12">
                        <label for="expense_name" class="form-label">
                            Nombre del gasto *
                        </label>

                        <input type="text" name="name" id="expense_name" class="form-control" placeholder="Ej.: Cuenta de electricidad" maxlength="150" required>
                        <small class="form-hint">
                            Ingresa un nombre descriptivo para el gasto.
                        </small>
                    </div>

                    {{-- Monto --}}
                    <div class="col-md-6">
                        <label for="amount" class="form-label">
                            Monto (CLP) *
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">$</span>

                            <input type="number" name="amount" id="amount" class="form-control" placeholder="45000" min="1" step="1" required>
                        </div>

                        <small class="form-hint">
                            Ingresa el monto en pesos chilenos.
                        </small>
                    </div>

                    {{-- Fecha --}}
                    <div class="col-md-6">
                        <label for="expense_date" class="form-label">
                            Fecha del gasto
                        </label>

                        <input type="date" name="expense_date" id="expense_date" class="form-control">
                        <small class="form-hint">
                            Fecha en que se realizó el gasto (opcional).
                        </small>
                    </div>

                    {{-- Recurrencia --}}
                    <div class="col-12 mt-4">
                        <label class="form-check">
                            <input type="checkbox" name="is_recurring" id="is_recurring" value="1" class="form-check-input">
                            <span class="form-check-label">
                                Gasto permanente (repetir en próximos meses)
                            </span>
                        </label>

                        <small class="form-hint ms-4">
                            Marca esta opción si el gasto debe repetirse
                            en futuros registros mensuales.
                        </small>
                    </div>

                    {{-- Descripción --}}
                    <div class="col-12 mt-3">
                        <label for="description" class="form-label">
                            Descripción (opcional)
                        </label>

                        <textarea name="description" id="description" class="form-control" rows="3" maxlength="1000" placeholder="Detalles adicionales...">
                        </textarea>

                        <small class="form-hint">
                            Puedes agregar observaciones o notas del gasto.
                        </small>
                    </div>

                </div>

                {{-- Acciones --}}
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">

                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="ti ti-refresh me-1"></i>
                        Limpiar
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>
                        Agregar gasto
                    </button>

                </div>
            </form>

        </div>
    </div>

</div>

@endsection
