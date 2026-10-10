
@extends('layouts.app')

@section('title', 'Agregar Registro Gasto - PocketTrack')

@section('content')

<div class="page-header mb-4">
    <div class="row align-items-center">

        <div class="col">
            <h2 class="page-title">
                <i class="ti ti-file-plus me-2"></i>
                Agregar Registro Gasto
            </h2>

            <div class="text-secondary mt-1">
                Crear una nueva contabilidad mensual.
            </div>
        </div>

    </div>
</div>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            <i class="ti ti-calendar-plus me-2"></i>
            Datos del registro mensual
        </h3>
    </div>

    <div class="card-body">

        <!-- Formulario visual: guardado pendiente -->
        <form id="monthlyAccountForm">

            <div class="row g-3">

                <!-- Mes -->
                <div class="col-md-6">
                    <label for="month" class="form-label">
                        Mes
                    </label>

                    <select name="month"
                            id="month"
                            class="form-select"
                            required>
                        <option value="">Seleccionar mes</option>

                        @foreach ([
                            1 => 'Enero',
                            2 => 'Febrero',
                            3 => 'Marzo',
                            4 => 'Abril',
                            5 => 'Mayo',
                            6 => 'Junio',
                            7 => 'Julio',
                            8 => 'Agosto',
                            9 => 'Septiembre',
                            10 => 'Octubre',
                            11 => 'Noviembre',
                            12 => 'Diciembre'
                        ] as $number => $name)

                            <option value="{{ $number }}"
                                @selected($number == now()->month)>
                                {{ $name }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <!-- Año -->
                <div class="col-md-6">
                    <label for="year" class="form-label">
                        Año
                    </label>

                    <input type="number"
                           name="year"
                           id="year"
                           class="form-control"
                           value="{{ now()->year }}"
                           min="2000"
                           max="2100"
                           required>
                </div>

                <!-- Ingreso mensual -->
                <div class="col-md-12">
                    <label for="income" class="form-label">
                        Ingreso mensual (CLP)
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">$</span>

                        <input type="number"
                               name="income"
                               id="income"
                               class="form-control"
                               min="0"
                               step="1"
                               value="0"
                               required>
                    </div>
                </div>

                <!-- Inicio del período -->
                <div class="col-md-6">
                    <label for="period_start" class="form-label">
                        Inicio del período
                    </label>

                    <input type="date"
                           name="period_start"
                           id="period_start"
                           class="form-control">
                </div>

                <!-- Fin del período -->
                <div class="col-md-6">
                    <label for="period_end" class="form-label">
                        Fin del período
                    </label>

                    <input type="date"
                           name="period_end"
                           id="period_end"
                           class="form-control">
                </div>

                <!-- Observaciones -->
                <div class="col-12">
                    <label for="notes" class="form-label">
                        Observaciones
                    </label>

                    <textarea name="notes"
                              id="notes"
                              class="form-control"
                              rows="3"
                              placeholder="Observaciones opcionales..."></textarea>
                </div>

            </div>

        </form>

    </div>

    <div class="card-footer d-flex justify-content-end gap-2">

        <a href="{{ route('dashboard') }}"
           class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>
            Cancelar
        </a>

        <button type="button"
                class="btn btn-primary"
                disabled
                title="Se habilitará al implementar el guardado">
            <i class="ti ti-device-floppy me-1"></i>
            Guardar registro
        </button>

    </div>

</div>

@endsection
