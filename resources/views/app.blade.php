<!DOCTYPE html>
<html lang="es">

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

<script>
    function cerrarModal() {
        var myModal = bootstrap.Modal.getInstance(document.getElementById('ajaxModal'));
        myModal.hide();
    }
    document.addEventListener("DOMContentLoaded", function() {
        // Variable para almacenar si se ha navegado hacia atrás
        let isBackNavigation = false;

        // Manejar el evento de navegación hacia atrás
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                isBackNavigation = true;
            }
        });

        // Puedes usar la variable isBackNavigation para condicionales adicionales
        window.isBackNavigation = isBackNavigation;
    });
</script>



<head>

    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Incluir Bootstrap u otros estilos -->
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <!-- Bootstrap CSS (si aún no lo has agregado) -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css" />-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Incluir el JS de Bootstrap u otros scripts -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>



    <!--<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>-->
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css', 'resources/css/mdb.min.css', 'resources/css/admin.css', 'resources/js/mdb.umd.min.js'])
</head>

<body>
    @php
        use Illuminate\Support\Facades\Auth;
    @endphp
    @if (Auth::check())
        <p>Usuario autenticado: {{ Auth::user()->name }}</p>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (window.isBackNavigation) {
                    console.log('El usuario ha navegado hacia atrás y está autenticado.');
                    // Puedes realizar más acciones aquí si es necesario
                } else {
                    console.log('El usuario no ha navegado hacia atrás.');
                }
            });
        </script>
    @else
        <p>No estás autenticado.</p>
    @endif
    @if (session()->has('user_permissions'))
        @php
            $permissions = session('user_permissions');
        @endphp
    @else
        @php
            $permissions = []; // O define un valor predeterminado si no hay permisos en la sesión
        @endphp
    @endif
    <!-- Barra de navegación o cualquier otra estructura común -->
    <!--<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Mi Aplicación</a>
    </nav>-->
    @if (!request()->routeIs('login'))
        <div class="bd-example">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                @include('nav')
            </nav>
        </div>
    @endif

    <div class="container" id="content">
        {{-- Acceder a los permisos desde la sesión --}}

        <!-- Aquí se inyectará el contenido específico de cada vista -->
        @yield('content')
    </div>

    <!-- Pie de página -->
    <footer class="text-center align-text-bottom mt-4">
        <p>© 2024 SA 2 - SISTEMA CONTABLE</p>
    </footer>



</body>

</html>
