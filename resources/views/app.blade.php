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

    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Estilos personalizados -->
    @vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/css/mdb.min.css', 'resources/css/admin.css'])
    @yield('style')
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

    <div class="container-fluid" id="content">
        @yield('content')
    </div>
    @yield('modalBody')

    <!-- Pie de página -->
    <footer class="footer">
        &copy; 2024 Sistema Contable. Todos los derechos reservados.
    </footer>

    <!-- jQuery (importado desde el CDN para asegurar que esté disponible globalmente) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@latest"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <!-- Scripts personalizados -->
    @vite(['resources/js/app.js', 'resources/js/mdb.umd.min.js'])
    @yield('script')

    <script>
        // Función para cerrar el modal
        function cerrarModal(id) {
            console.log('Intentando cerrar el modal con ID: ' + id);
            var myModal = bootstrap.Modal.getInstance(document.getElementById(id));
            console.log(myModal);
            if (myModal) {
                myModal.hide();
                console.log('Modal cerrado.');
            } else {
                console.error('No se encontró la instancia del modal con el ID: ' + id);
            }
        }
    </script>
</body>

</html>
