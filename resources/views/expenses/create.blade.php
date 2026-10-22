
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Encabezado --}}
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Nuevo gasto</h2>
                <div class="text-secondary mt-1">
                    Registrar un gasto reutilizable en el catálogo.
                </div>
            </div>

            <div class="col-auto">
                <a href="{{ route('expenses.index') }}"
                   class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-2"></i>
                    Volver al catálogo
                </a>
            </div>
        </div>
    </div>

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Revisa los siguientes errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información del gasto</h3>
        </div>

        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf

            <div class="card-body">
                <div class="row g-3">

                    {{-- Nombre --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">
                            Nombre del gasto
                        </label>

                        <input type="text"
                               id="name"
                               name="name"
                               class="form-control"
                               maxlength="100"
                               value="{{ old('name') }}"
                               placeholder="Ejemplo: Cuenta de luz"
                               required>
                    </div>

                    {{-- Categoría --}}
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">
                            Categoría
                        </label>

                        <select id="category_id"
                                name="category_id"
                                class="form-select"
                                required>
                            <option value="">Seleccionar categoría</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Descripción --}}
                    <div class="col-12">
                        <label for="description" class="form-label">
                            Descripción
                        </label>

                        <textarea id="description"
                                  name="description"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Descripción opcional del gasto">{{ old('description') }}</textarea>
                    </div>

                    {{-- Permanente --}}
                    <div class="col-md-6">
                        <input type="hidden" name="is_permanent" value="0">

                        <label class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_permanent"
                                   value="1"
                                   @checked(old('is_permanent', '1') == '1')>

                            <span class="form-check-label">
                                Gasto permanente
                            </span>
                        </label>

                        <small class="form-hint">
                            Identifica un gasto que podrá reutilizarse
                            en distintos registros mensuales.
                        </small>
                    </div>

                    {{-- Estado --}}
                    <div class="col-md-6">
                        <input type="hidden" name="is_active" value="0">

                        <label class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_active"
                                   value="1"
                                   @checked(old('is_active', '1') == '1')>

                            <span class="form-check-label">
                                Gasto activo
                            </span>
                        </label>

                        <small class="form-hint">
                            Los gastos inactivos podrán conservarse
                            sin utilizarlos temporalmente.
                        </small>
                    </div>

                </div>
            </div>

            {{-- Acciones --}}
            <div class="card-footer text-end">
                <a href="{{ route('expenses.index') }}"
                   class="btn btn-outline-secondary me-2">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-2"></i>
                    Guardar gasto
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
