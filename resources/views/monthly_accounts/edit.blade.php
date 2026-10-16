
@extends('layouts.app')

@section('title', 'Agregar Registro Gasto - PocketTrack')

@section('content')


<h2 class="page-title">
    <i class="ti ti-edit me-2"></i>
    Editar registro mensual
</h2>

<p class="text-secondary">
    Modificar los datos de una contabilidad mensual existente.
</p>


<div class="card">

    <div class="card-header">
        <h3 class="card-title"> <i class="ti ti-calendar-plus me-2"></i>
            Datos del registro mensual
        </h3>
    </div>

    <div class="card-body">
        
        
        <form action="{{ route('monthly-accounts.update', $monthlyAccount) }}" method="POST">
        @csrf
        @method('PUT')    

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
                                @selected((int) old('month', $monthlyAccount->month) === (int) $number)>
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
                    <input type="number" name="year" id="year" class="form-control" value="{{ old('year', $monthlyAccount->year) }}" min="2000" max="2100" required>
                </div>

                <!-- Ingreso mensual -->
                <div class="col-md-12">
                    <label for="income" class="form-label">
                        Ingreso mensual (CLP)
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="income" id="income" class="form-control" min="0" step="1" value="{{ old('income', $monthlyAccount->income) }}" required>
                    </div>
                </div>

                <!-- Inicio del período -->
                <div class="col-md-6">
                    <label for="period_start" class="form-label">
                        Inicio del período
                    </label>
                    <input type="date" name="period_start" id="period_start" class="form-control" value="{{ old('period_start', $monthlyAccount->period_start ? \Illuminate\Support\Carbon::parse($monthlyAccount->period_start)->format('Y-m-d') : '') }}">
                </div>

                <!-- Fin del período -->
                <div class="col-md-6">
                    <label for="period_end" class="form-label">
                        Fin del período
                    </label>
                    <input type="date" name="period_end" id="period_end" class="form-control" value="{{ old('period_end', $monthlyAccount->period_end ? \Illuminate\Support\Carbon::parse($monthlyAccount->period_end)->format('Y-m-d') : '') }}">
                </div>

                <!-- Observaciones -->
                <div class="col-12">
                    <label for="notes" class="form-label">
                        Observaciones
                    </label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Observaciones adicionales del registro mensual...">{{ old('notes', $monthlyAccount->notes) }}</textarea>
                </div>

            </div>

            
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('monthly-accounts.show', $monthlyAccount) }}" class="btn btn-outline-secondary">
                    <i class="ti ti-x me-1"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i>
                    Guardar cambios
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
