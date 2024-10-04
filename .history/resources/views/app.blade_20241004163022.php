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

     <!-- Spinner que aparecerá al cargar la página -->
     <!--<div id="loading-overlay">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>-->
    <!-- Barra de navegación -->
    <div class="container-fluid" id="content">
        <div class="row">
            <!-- Columna del menú lateral -->
            <div class="col-md-2">
                <!-- Menú lateral -->
                @if (!request()->routeIs('login') && !request()->routeIs('showRegisterForm') && !request()->query('modal'))
                    <nav class="navbar navbar-expand-lg navbar-light bg-light">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="sidebarMenu">
                            @include('nav') <!-- Aquí va tu menú lateral -->
                        </div>
                    </nav>
                @endif
            </div>

            <!-- Columna del contenido principal -->
            <div class="col-md-10">
                <!-- Aquí se inyectará el contenido específico de cada vista -->
                @yield('content')
            </div>
        </div>
    </div>


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
        };

        /*window.onload = function() {
            document.getElementById('loading-overlay').style.display = 'none';
            document.getElementById('app-content').style.display = 'block';
        };

        var modalOpen = false;

        // Cuando se abre un modal
        document.addEventListener('shown.bs.modal', function () {
            modalOpen = true; // Cambiar el estado de modal abierto a true
        });

        // Cuando se cierra un modal
        document.addEventListener('hidden.bs.modal', function () {
            modalOpen = false; // Cambiar el estado de modal abierto a false
        });

        // Antes de mostrar el spinner en futuras cargas, verificar si un modal está abierto
        window.addEventListener('beforeunload', function (event) {
            if (!modalOpen) {
                document.getElementById('loading-overlay').style.display = 'flex';
                document.getElementById('app-content').style.display = 'none';
            }
        });*/
    </script>
</body>

</html>
