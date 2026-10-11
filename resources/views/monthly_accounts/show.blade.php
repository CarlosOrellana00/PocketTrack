
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
    <div class="card">

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
    </div>

</div>

@endsection
