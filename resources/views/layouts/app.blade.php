
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'PocketTrack')</title>

    <script>
        const savedTheme = localStorage.getItem('pockettrack-theme') || 'dark';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>

    <!-- Tabler CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.6.1/dist/css/tabler.min.css">

    <!-- Iconos de Tabler -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.36.1/dist/tabler-icons.min.css">
</head>

<body>
    <div class="page">

        <!-- Menú lateral -->
        <aside class="navbar navbar-vertical navbar-expand-lg"
               data-bs-theme="dark">
            <div class="container-fluid">

                <button class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#sidebar-menu"
                        aria-controls="sidebar-menu"
                        aria-expanded="false"
                        aria-label="Abrir menú">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-brand">
                    <a href="{{ route('dashboard') }}"
                       class="text-decoration-none text-reset">
                        PocketTrack
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-3">

                        <li class="nav-item">
                            <a class="nav-link active d-flex align-items-center gap-3 py-3"
                            href="{{ route('dashboard') }}">

                                <span class="nav-link-icon">
                                    <i class="ti ti-home fs-2"></i>
                                </span>

                                <span class="nav-link-title">
                                    Inicio
                                </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <span class="nav-link d-flex align-items-center gap-3 py-3 text-secondary">

                                <span class="nav-link-icon">
                                    <i class="ti ti-circle-plus fs-2"></i>
                                </span>

                                <span class="nav-link-title">
                                    Agregar Gasto
                                </span>
                            </span>
                        </li>

                        <li class="nav-item">
                            <span class="nav-link d-flex align-items-center gap-3 py-3 text-secondary">

                                <span class="nav-link-icon">
                                    <i class="ti ti-list-details fs-2"></i>
                                </span>

                                <span class="nav-link-title">
                                    Catálogo de gastos
                                    <small class="d-block text-secondary">
                                        Próximamente
                                    </small>
                                </span>
                            </span>
                        </li>

                        <li class="nav-item">
                            <span class="nav-link d-flex align-items-center gap-3 py-3 text-secondary">

                                <span class="nav-link-icon">
                                    <i class="ti ti-chart-bar fs-2"></i>
                                </span>

                                <span class="nav-link-title">
                                    Comparativas
                                    <small class="d-block text-secondary">
                                        Próximamente
                                    </small>
                                </span>
                            </span>
                        </li>

                    </ul>
                </div>
            </div>
        </aside>

        <!-- Contenido principal -->
        <div class="page-wrapper">

            <header class="navbar navbar-expand-md">
                <div class="container-xl">

                    <div class="navbar-nav flex-row ms-auto">
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm"
                                id="theme-toggle">
                            Cambiar tema
                        </button>
                    </div>

                </div>
            </header>

            <div class="page-body">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>

            <footer class="footer footer-transparent mt-auto">
                <div class="container-xl text-center">
                    <small class="text-secondary">
                        PocketTrack — Proyecto personal de práctica
                    </small>
                </div>
            </footer>

        </div>
    </div>

    <!-- Tabler JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.6.1/dist/js/tabler.min.js"></script>

    <script>
        document.getElementById('theme-toggle').addEventListener('click', function () {
            const html = document.documentElement;
            const current = html.getAttribute('data-bs-theme');
            const next = current === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-bs-theme', next);
            localStorage.setItem('pockettrack-theme', next);
        });
    </script>
</body>
</html>
