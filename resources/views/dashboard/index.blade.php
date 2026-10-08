
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PocketTrack - Dashboard</title>
</head>
<body>

    <h1>PocketTrack</h1>

    <h2>Panel principal</h2>

    <p>Bienvenido al sistema de contabilidad personal.</p>

    <h3>Últimos registros mensuales</h3>

    @if ($monthlyAccounts->isEmpty())
        <p>Todavía no existen registros mensuales.</p>
    @else
        <ul>
            @foreach ($monthlyAccounts as $account)
                <li>
                    Gastos {{ $account->month }}/{{ $account->year }}
                    - Ingreso: ${{ number_format($account->income, 0, ',', '.') }}
                </li>
            @endforeach
        </ul>
    @endif

</body>
</html>
