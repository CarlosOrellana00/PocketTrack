
@extends('layouts.app')

@section('title', 'Dashboard - PocketTrack')

@section('content')

<div class="page-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">
                Panel principal
            </h2>
            <div class="text-secondary mt-1">
                Administración de contabilidades mensuales
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Últimos registros mensuales
        </h3>
    </div>

    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Ingreso mensual</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($monthlyAccounts as $account)
                    <tr>
                        <td>
                            Gastos
                            {{ $account->month }}/{{ $account->year }}
                        </td>
                        <td>
                            ${{ number_format($account->income, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center text-secondary py-4">
                            Todavía no existen registros mensuales.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
