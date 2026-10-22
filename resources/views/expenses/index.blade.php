@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Encabezado --}}
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Catálogo de gastos
                </h2>
                <div class="text-secondary mt-1">
                    Administración de gastos reutilizables y permanentes.
                </div>
            </div>

            <div class="col-auto">
                <a href="{{ route('expenses.create') }}"
                   class="btn btn-primary">
                    <i class="ti ti-plus me-2"></i>
                    Nuevo gasto
                </a>
            </div>
        </div>
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- Tabla --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Gastos registrados
            </h3>
        </div>

        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th>Permanente</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td class="fw-semibold">
                                {{ $expense->name }}
                            </td>

                            <td>
                                {{ $expense->category?->name ?? 'Sin categoría' }}
                            </td>

                            <td>
                                {{ $expense->description ?: 'Sin descripción' }}
                            </td>

                            <td>
                                @if($expense->is_permanent)
                                    <span class="badge bg-blue-lt">
                                        Sí
                                    </span>
                                @else
                                    <span class="badge bg-secondary-lt">
                                        No
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($expense->is_active)
                                    <span class="badge bg-green-lt">
                                        Activo
                                    </span>
                                @else
                                    <span class="badge bg-red-lt">
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            <td class="text-end">
                                <div class="d-inline-flex gap-2">

                                    <a href="{{ route('expenses.edit', $expense) }}"
                                       class="btn btn-outline-info btn-icon"
                                       title="Editar gasto"
                                       aria-label="Editar gasto">
                                        <i class="ti ti-edit"></i>
                                    </a>

                                    <form action="{{ route('expenses.destroy', $expense) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este gasto del catálogo?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-outline-danger btn-icon"
                                                title="Eliminar gasto"
                                                aria-label="Eliminar gasto">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="text-center text-secondary py-4">
                                Todavía no hay gastos registrados en el catálogo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($expenses->hasPages())
            <div class="card-footer">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

</div>
@endsection