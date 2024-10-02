<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema Contable')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

    <!-- Estilos personalizados -->
    @vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/css/mdb.min.css', 'resources/css/admin.css'])
    @yield('style') <!-- Sección para estilos específicos de cada vista -->

    <style>
        .navbar-dark .nav-item .nav-link {
            color: #fff;
        }

        .navbar-dark .nav-item .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            border-radius: 0.25rem;
            color: #fff;
        }

        .fa-li {
            position: relative;
            left: 0;
        }
    </style>
</head>

<body>
    <!-- Barra de navegación -->
    @if (!request()->routeIs('login') && !request()->routeIs('showRegisterForm') && !request()->query('modal'))
        <div class="bd-example">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                @include('nav')
            </nav>
        </div>
    @endif

    <div class="container" id="content">

        <!-- Aquí se inyectará el contenido específico de cada vista -->
        @yield('content')
    </div>
    @yield('modalBody')

    <!-- Pie de página -->
    <footer class="footer">
        &copy; 2024 Sistema Contable. Todos los derechos reservados.
    </footer>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <!-- Scripts personalizados -->
    @vite(['resources/js/app.js', 'resources/js/mdb.umd.min.js'])
    @yield('script') <!-- Sección para scripts específicos de cada vista -->

    <script>
        // Función para cerrar el modal
        document.addEventListener('cerrarModal', function() {
            var myModal = bootstrap.Modal.getInstance(document.getElementById('ajaxModal'));
            if (myModal) {
                myModal.hide();
            }
        });
    </script>
</body>

</html>
