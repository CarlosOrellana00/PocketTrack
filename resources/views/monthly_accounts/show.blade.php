
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
                                                    <button type="button" class="btn btn-outline-info btn-icon" title="Editar gasto"
                                                        aria-label="Editar gasto"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditarGasto"
                                                        data-gasto-id="{{ $gasto->id }}"
                                                        data-gasto-nombre="{{ $gasto->name }}"
                                                        data-gasto-categoria="{{ $gasto->category_id }}"
                                                        data-gasto-monto="{{ $gasto->amount }}"
                                                        data-gasto-fecha="{{ $gasto->expense_date?->format('Y-m-d') }}"
                                                        data-gasto-descripcion="{{ $gasto->description }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <!-- Botón para solicitar eliminación -->
                                                    <button type="button" class="btn btn-outline-danger btn-icon"
                                                        title="Eliminar gasto" data-bs-toggle="modal" data-bs-target="#modalEliminarGasto"
                                                        data-gasto-id="{{ $gasto->id }}" data-gasto-nombre="{{ $gasto->expense->name ?? 'este gasto' }}">
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
                <!-- Modal de confirmación para eliminar gasto -->
                <div class="modal fade" id="modalEliminarGasto" tabindex="-1" aria-labelledby="tituloEliminarGasto" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="tituloEliminarGasto">
                                    <i class="ti ti-alert-triangle text-danger me-2"></i>
                                    Confirmar eliminación
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>
                                    ¿Estás seguro de que deseas eliminar
                                    <strong id="nombreGastoEliminar"></strong>?
                                </p>
                                <p class="text-secondary mb-0">
                                    Esta acción no se puede deshacer.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    No, cancelar
                                </button>
                                <!-- <form id="formEliminarGasto" method="POST" action="" onsubmit="return this.action !== window.location.href && this.dataset.ready === 'true';">     -->
                                <form id="formEliminarGasto" method="POST" action="">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="ti ti-trash me-1"></i>
                                        Sí, eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Modal de edición rápida de gastos --}}
                <div class="modal fade" id="modalEditarGasto" tabindex="-1" aria-labelledby="tituloEditarGasto" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form id="formEditarGasto" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="tituloEditarGasto">
                                        <i class="ti ti-edit text-info me-2"></i>
                                        Editar gasto
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{-- Nombre --}}
                                    <div class="mb-3">
                                        <label for="editarNombre" class="form-label">
                                            Nombre del gasto
                                        </label>
                                        <input type="text" class="form-control" id="editarNombre" name="name" maxlength="100" required>
                                    </div>
                                    {{-- Categoría --}}
                                    <div class="mb-3">
                                        <label for="editarCategoria" class="form-label">
                                            Categoría
                                        </label>
                                        <select class="form-select" id="editarCategoria" name="category_id" required>
                                            <option value="">Seleccionar categoría</option>
                                            @foreach (\App\Models\Category::orderBy('name')->get() as $categoria)
                                                <option value="{{ $categoria->id }}">
                                                    {{ $categoria->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- Monto --}}
                                    <div class="mb-3">
                                        <label for="editarMonto" class="form-label">
                                            Monto (CLP)
                                        </label>

                                        <div class="input-group">
                                            <span class="input-group-text">$</span>

                                            <input type="number" class="form-control" id="editarMonto" name="amount" min="0.01" step="0.01" required>
                                        </div>
                                    </div>
                                    {{-- Fecha --}}
                                    <div class="mb-3">
                                        <label for="editarFecha" class="form-label">
                                            Fecha del gasto
                                        </label>
                                        <input type="date" class="form-control" id="editarFecha" name="expense_date">
                                    </div>
                                    {{-- Descripción --}}
                                    <div class="mb-3">
                                        <label for="editarDescripcion" class="form-label">
                                            Descripción
                                        </label>
                                        <textarea class="form-control" id="editarDescripcion" name="description" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i>
                                        Guardar cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
                                    @selected(old('category_id') == $category->id)>
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

{{-- JavaScript del modal de edición --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modalEditar = document.getElementById('modalEditarGasto');
    const formulario = document.getElementById('formEditarGasto');

    if (!modalEditar || !formulario) {
        return;
    }

    modalEditar.addEventListener('show.bs.modal', function (event) {

        const boton = event.relatedTarget;

        if (!boton) {
            return;
        }

        const gastoId = boton.getAttribute('data-gasto-id');

        const rutaBase = @json(url('/registros/' . $monthlyAccount->id . '/gastos'));

        formulario.action = rutaBase + '/' + encodeURIComponent(gastoId);

        document.getElementById('editarNombre').value =
            boton.getAttribute('data-gasto-nombre') ?? '';

        document.getElementById('editarCategoria').value =
            boton.getAttribute('data-gasto-categoria') ?? '';

        document.getElementById('editarMonto').value =
            boton.getAttribute('data-gasto-monto') ?? '';

        document.getElementById('editarFecha').value =
            boton.getAttribute('data-gasto-fecha') ?? '';

        document.getElementById('editarDescripcion').value =
            boton.getAttribute('data-gasto-descripcion') ?? '';

    });
});


/**
 * Configurar el modal para eliminar un gasto individual.
 */
document.addEventListener('DOMContentLoaded', function () {

    const modalEliminar = document.getElementById('modalEliminarGasto');
    const formularioEliminar = document.getElementById('formEliminarGasto');
    const nombreEliminar = document.getElementById('nombreGastoEliminar');

    if (!modalEliminar || !formularioEliminar) {
        return;
    }

    // Impedir cualquier envío sin una ruta validada.
    formularioEliminar.addEventListener('submit', function (event) {
        if (formularioEliminar.dataset.ready !== 'true') {
            event.preventDefault();
        }
    });

    modalEliminar.addEventListener('show.bs.modal', function (event) {

        const boton = event.relatedTarget;

        formularioEliminar.removeAttribute('action');
        formularioEliminar.dataset.ready = 'false';

        if (!boton) {
            return;
        }

        const gastoId = boton.getAttribute('data-gasto-id');
        const gastoNombre = boton.getAttribute('data-gasto-nombre');

        if (nombreEliminar) {
            nombreEliminar.textContent = gastoNombre || 'este gasto';
        }

        if (!gastoId || !/^\d+$/.test(gastoId)) {
            return;
        }

        const rutaBase = @json(url('/registros/' . $monthlyAccount->id . '/gastos'));

        formularioEliminar.action =
            rutaBase + '/' + encodeURIComponent(gastoId);

        formularioEliminar.dataset.ready = 'true';
    });
});

</script>

@endsection

