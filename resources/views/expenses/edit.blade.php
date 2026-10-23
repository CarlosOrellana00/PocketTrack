
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Encabezado --}}
    <div class="row align-items-center mb-4">
        <div class="col">
            <h2 class="page-title">Editar gasto</h2>
            <div class="text-secondary">
                Modificar la información de un gasto del catálogo.
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

    {{-- Mensajes de error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>No se pudieron guardar los cambios.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario de edición --}}
    <div class="card">
        <form action="{{ route('expenses.update', $expense) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="card-header">
                <h3 class="card-title">
                    Información del gasto
                </h3>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    {{-- Nombre --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">
                            Nombre del gasto
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            maxlength="100"
                            value="{{ old('name', $expense->name) }}"
                            required
                        >
                    </div>

                    {{-- Categoría --}}
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">
                            Categoría
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                            class="form-select"
                            required
                        >
                            <option value="">
                                Seleccionar categoría
                            </option>

                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected(
                                        old('category_id', $expense->category_id)
                                        == $category->id
                                    )
                                >
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

                        <textarea
                            id="description"
                            name="description"
                            class="form-control"
                            rows="3"
                            placeholder="Descripción opcional del gasto"
                        >{{ old('description', $expense->description) }}</textarea>
                    </div>

                    {{-- Gasto permanente --}}
                    <div class="col-md-6">

                        <input
                            type="hidden"
                            name="is_permanent"
                            value="0"
                        >

                        <label class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_permanent"
                                value="1"
                                @checked(old('is_permanent', $expense->is_permanent))
                            >

                            <span class="form-check-label">
                                Gasto permanente
                            </span>
                        </label>

                        <small class="form-hint">
                            Identifica un gasto que podrá reutilizarse
                            en distintos registros mensuales.
                        </small>
                    </div>

                    {{-- Gasto activo --}}
                    <div class="col-md-6">

                        <input
                            type="hidden"
                            name="is_active"
                            value="0"
                        >

                        <label class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_active"
                                value="1"
                                @checked(old('is_active', $expense->is_active))
                            >

                            <span class="form-check-label">
                                Gasto activo
                            </span>
                        </label>

                        <small class="form-hint">
                            Los gastos inactivos se conservan,
                            pero pueden dejar de utilizarse temporalmente.
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
                    Guardar cambios
                </button>

            </div>

        </form>
    </div>

</div>
@endsection
