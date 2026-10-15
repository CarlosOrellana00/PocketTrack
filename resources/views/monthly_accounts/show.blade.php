
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

                <div class="col-md-6">
                    <strong>Mes:</strong>
                    <p>{{ $monthlyAccount->month }}</p>
                </div>

                <div class="col-md-6">
                    <strong>Año:</strong>
                    <p>{{ $monthlyAccount->year }}</p>
                </div>

                <div class="col-md-12">
                    <strong>Ingreso mensual:</strong>
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

            @if ($monthlyAccount->expenseItems->isEmpty())

                <div class="text-center py-4">
                    <i class="ti ti-receipt-off fs-1 text-muted"></i>

                    <p class="text-muted mt-2">
                        Todavía no se han registrado gastos
                        en este documento.
                    </p>
                </div>

            @else

                <p class="text-muted">
                    Hay {{ $monthlyAccount->expenseItems->count() }}
                    gastos registrados.
                </p>

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

            {{-- Mostrar errores de validación del formulario --}}


        <!--errores de formulario  -->
        <!-- @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">

                <div class="d-flex align-items-center mb-2">
                    <i class="ti ti-alert-circle me-2"></i>
                    <strong>No se pudo registrar el gasto.</strong>
                </div>

                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Cerrar">
                </button>

            </div>
         @endif -->

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

    <!-- <div class="card">

        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-plus me-2"></i>
                Agregar gasto
            </h5>
        </div>

        <div class="card-body">

            <p class="text-muted mb-0">
                Aquí incorporaremos el formulario para
                registrar gastos fijos, personales,
                circunstanciales, imprevistos,
                gustos, ahorros y deudas.
            </p>

        </div>
    </div> -->

</div>

@endsection
