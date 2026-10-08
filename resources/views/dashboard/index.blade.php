
@extends('layouts.app')

@section('title', 'Dashboard - PocketTrack')

@section('content')

<!-- Encabezado y herramientas -->
<div class="d-flex flex-wrap align-items-center gap-3 mb-4">

    <span class="fw-bold fs-2">
        Contabilidades mensuales
    </span>

    <div class="ms-auto d-flex flex-wrap align-items-center gap-2">

        <!-- Botón de creación (pendiente de ruta) -->
        <button type="button" class="btn btn-primary" disabled>
            <i class="ti ti-plus me-1"></i>
            Agregar Gasto
        </button>

        <!-- Búsqueda por texto -->
        <div class="input-icon">
            <span class="input-icon-addon">
                <i class="ti ti-search"></i>
            </span>

            <input type="search"
                   id="searchDocuments"
                   class="form-control"
                   placeholder="Buscar documento...">
        </div>

    </div>
</div>

<!-- Tabla de contabilidades -->
<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            <i class="ti ti-files me-2"></i>
            Últimos registros mensuales
        </h3>
    </div>

    <div class="table-responsive">

        <table class="table table-vcenter card-table"
               id="monthlyAccountsTable">

            <thead>
                <tr>
                    <th class="text-center" style="width: 70px;">
                        N°
                    </th>

                    <th>
                        Documento
                    </th>

                    <th class="text-center">
                        Fecha de cierre
                    </th>

                    <th class="text-center">
                        Acciones
                    </th>
                </tr>
            </thead>

            <tbody>

                @forelse ($monthlyAccounts as $account)

                    @php
                        $monthName = \Illuminate\Support\Carbon::create(
                            $account->year,
                            $account->month,
                            1
                        )->locale('es')->translatedFormat('F');

                        $documentName = 'Gastos '
                            . ucfirst($monthName)
                            . ' '
                            . $account->year;
                    @endphp

                    <tr class="document-row">

                        <!-- Posición -->
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>

                        <!-- Nombre del documento -->
                        <td class="document-name">
                            <i class="ti ti-file-text me-2 text-secondary"></i>
                            {{ $documentName }}
                        </td>

                        <!-- Fecha de cierre -->
                        <td class="text-center">

                            @if ($account->closed_at)
                                {{ $account->closed_at->format('d/m/Y') }}
                            @else
                                <span class="badge bg-yellow-lt">
                                    Pendiente
                                </span>
                            @endif

                        </td>

                        <!-- Acciones -->
                        <td class="text-center">

                            <div class="d-flex justify-content-center gap-2">

                                <button class="btn btn-sm btn-outline-secondary"
                                        title="Editar"
                                        disabled>
                                    <i class="ti ti-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-danger"
                                        title="Exportar PDF"
                                        disabled>
                                    <i class="ti ti-file-type-pdf"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-success"
                                        title="Exportar Excel"
                                        disabled>
                                    <i class="ti ti-file-spreadsheet"></i>
                                </button>

                            </div>

                        </td>
                    </tr>

                @empty

                    <tr id="emptyState">
                        <td colspan="4"
                            class="text-center text-secondary py-4">

                            <i class="ti ti-folder-off fs-1 d-block mb-2"></i>

                            Todavía no existen registros mensuales.

                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>

    </div>
</div>

<!-- Filtro visual de búsqueda -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchDocuments');
    const rows = document.querySelectorAll('.document-row');

    searchInput.addEventListener('input', function () {

        const search = this.value.toLowerCase().trim();

        rows.forEach(function (row) {

            const documentName = row.querySelector('.document-name')
                .textContent.toLowerCase();

            row.style.display = documentName.includes(search)
                ? ''
                : 'none';
        });
    });
});
</script>

@endsection
