@extends('app')

@section('content')
    <!-- Scripts -->

    <script type="text/javascript">
        var table = null;
        $(document).ready(function($) {

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('usuarios.data') }}", // Ruta del controlador
                    type: "POST", // Cambiar el método a POST
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Agregar el token CSRF
                    }
                },
                columns: [{
                        data: 'idusuario',
                        name: 'idusuario'
                    },
                    {
                        data: 'usuario',
                        name: 'usuario'
                    },
                    {
                        data: 'cuil',
                        name: 'cuil'
                    },
                    {
                        data: 'apellido',
                        name: 'apellido'
                    },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'edit',
                        name: 'edit',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'delete',
                        name: 'delete',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            setInterval(function() {
                table.ajax.reload();
            }, 30000);

            // Manejar clic en el botón de eliminación
            $('#myTable').on('click', '.delete', function() {

                var id = $(this).data('id');
                if (id === undefined) {
                    console.error('ID no definido');
                    return;
                }
                if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
                    $.ajax({
                        url: "{{ route('usuarios.destroy') }}",
                        type: 'POST',
                        data: {
                            id: id,
                            _method: 'post', // Aunque este método se usa solo con el método DELETE en REST, aquí está para claridad
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                // Refrescar la tabla DataTable
                                table.ajax.reload();
                                alert('Usuario eliminado con éxito.');
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            alert('Error al realizar la solicitud.');
                        }
                    });
                }
            });

            // Manejar clic en el botón de eliminación
            $('#myTable').on('click', '.edit', function() {
                var id = $(this).data('id');
                if (id === undefined) {
                    console.error('ID no definido');
                    return;
                }

                $.ajax({
                    url: "{{ route('usuarios.editarUsuario') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _method: 'post',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // El token CSRF desde el meta
                    },
                    success: function(response) {
                        // Cargar el contenido del modal en el contenedor
                        $('#modalContainer').html(response);

                        // Seleccionar el modal de Bootstrap 5
                        var myModal = new bootstrap.Modal(document.getElementById('ajaxModal'));

                        // Mostrar el modal
                        myModal.show();

                    },
                    error: function(xhr) {
                        console.error('Error al cargar el modal');
                    }
                });

            });
        });

        function agregarUsuario() {
            $.ajax({
                url: "{{ route('usuarios.agregarUsuario') }}",
                type: "POST",
                //data: data,                
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // El token CSRF desde el meta
                },
                success: function(response) {
                    // Cargar el contenido del modal en el contenedor
                    $('#modalContainer').html(response);

                    // Seleccionar el modal de Bootstrap 5
                    var myModal = new bootstrap.Modal(document.getElementById('ajaxModal'));

                    // Mostrar el modal
                    myModal.show();
                },
                error: function(xhr) {
                    console.error('Error al cargar el modal');
                }
            });
        }

        function guardarUsuario() {
            let data = {
                cuil: $("#cuil").val(),
                apellido: $("#apellido").val(),
                nombre: $("#nombre").val(),
                email: $("#email").val(),
                clave: $("#clave").val(),
                usuario: $("#usuario").val()
            }

            var url = "{{ route('usuarios.guardarUsuario') }}";

            $.ajax({
                url: url,
                type: "post",
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // El token CSRF desde el meta
                },
                //error: swalError,
                success: function(json) {
                    table.ajax.reload();
                    cerrarModal();
                    /*swal({
                        icon: (json.status === 200) ? "success" : "error",
                        text: json.msg
                    }).then(function() {
                        cerrarModal();

                    });*/

                }
            });
        }

        function actualizarUsuario(idusuario) {
            let data = {
                id: idusuario,
                cuil: $("#cuil").val(),
                apellido: $("#apellido").val(),
                nombre: $("#nombre").val(),
                email: $("#email").val(),
                clave: $("#clave").val(),
                usuario: $("#usuario").val()
            }

            var url = "{{ route('usuarios.actualizarUsuario') }}";

            $.ajax({
                url: url,
                type: "post",
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // El token CSRF desde el meta
                },
                //error: swalError,
                success: function(json) {
                    table.ajax.reload();
                    cerrarModal();
                    /*swal({
                        icon: (json.status === 200) ? "success" : "error",
                        text: json.msg
                    }).then(function() {
                        cerrarModal();

                    });*/

                }
            });
        }
    </script>

    <div class="bd-example">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            @include('nav')
        </nav>
    </div>

    <main style="margin-top: 58px">
        <div class="container pt-4">
            <!-- Section: Main chart -->
            <section class="mb-4">
                <div class="card">
                    <div class="card-header py-3">
                        <h5 class="mb-0 text-center"><strong>Usuarios</strong>
                            <i class="fas fa-user-plus position-absolute end-0 me-3"
                                style="font-size: 1.3rem; cursor: pointer;" onclick="agregarUsuario()"></i>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <div id="modalContainer"></div>
                        <div class="table-responsive">
                            <table id="myTable" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Cuil</th>
                                        <th>Apellido</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Editar</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
        </div>
        </section>
        <!-- Section: Main chart -->

        <!--Section: Sales Performance KPIs-->
        <section class="mb-4">
            <div class="card">
                <div class="card-header text-center py-3">
                    <h5 class="mb-0 text-center">
                        <strong>Sales Performance KPIs</strong>
                    </h5>
                </div>
                <div class="card-body">

                </div>
        </section>
        <!--Section: Sales Performance KPIs-->

        <!--Section: Minimal statistics cards-->
        <section>
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i class="fas fa-pencil-alt text-info fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    <h3>278</h3>
                                    <p class="mb-0">New Posts</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i class="far fa-comment-alt text-warning fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    <h3>156</h3>
                                    <p class="mb-0">New Comments</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i class="fas fa-chart-line text-success fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    <h3>64.89 %</h3>
                                    <p class="mb-0">Bounce Rate</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i class="fas fa-map-marker-alt text-danger fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    <h3>423</h3>
                                    <p class="mb-0">Total Visits</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div>
                                    <h3 class="text-danger">278</h3>
                                    <p class="mb-0">New Projects</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-rocket text-danger fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div>
                                    <h3 class="text-success">156</h3>
                                    <p class="mb-0">New Clients</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="far fa-user text-success fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div>
                                    <h3 class="text-warning">64.89 %</h3>
                                    <p class="mb-0">Conversion Rate</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-chart-pie text-warning fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div>
                                    <h3 class="text-info">423</h3>
                                    <p class="mb-0">Support Tickets</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="far fa-life-ring text-info fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div>
                                    <h3 class="text-info">278</h3>
                                    <p class="mb-0">New Posts</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-book-open text-info fa-3x"></i>
                                </div>
                            </div>
                            <div class="px-md-1">
                                <div class="progress mt-3 mb-1 rounded" style="height: 7px">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                                        aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div>
                                    <h3 class="text-warning">156</h3>
                                    <p class="mb-0">New Comments</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="far fa-comments text-warning fa-3x"></i>
                                </div>
                            </div>
                            <div class="px-md-1">
                                <div class="progress mt-3 mb-1 rounded" style="height: 7px">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 35%"
                                        aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div>
                                    <h3 class="text-success">64.89 %</h3>
                                    <p class="mb-0">Bounce Rate</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-mug-hot text-success fa-3x"></i>
                                </div>
                            </div>
                            <div class="px-md-1">
                                <div class="progress mt-3 mb-1 rounded" style="height: 7px">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 60%"
                                        aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div>
                                    <h3 class="text-danger">423</h3>
                                    <p class="mb-0">Total Visits</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-map-signs text-danger fa-3x"></i>
                                </div>
                            </div>
                            <div class="px-md-1">
                                <div class="progress mt-3 mb-1 rounded" style="height: 7px">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 40%"
                                        aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Section: Minimal statistics cards-->

        <!--Section: Statistics with subtitles-->
        <section>
            <div class="row">
                <div class="col-xl-6 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between p-md-1">
                                <div class="d-flex flex-row">
                                    <div class="align-self-center">
                                        <i class="fas fa-pencil-alt text-info fa-3x me-4"></i>
                                    </div>
                                    <div>
                                        <h4>Total Posts</h4>
                                        <p class="mb-0">Monthly blog posts</p>
                                    </div>
                                </div>
                                <div class="align-self-center">
                                    <h2 class="h1 mb-0">18,000</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between p-md-1">
                                <div class="d-flex flex-row">
                                    <div class="align-self-center">
                                        <i class="far fa-comment-alt text-warning fa-3x me-4"></i>
                                    </div>
                                    <div>
                                        <h4>Total Comments</h4>
                                        <p class="mb-0">Monthly blog posts</p>
                                    </div>
                                </div>
                                <div class="align-self-center">
                                    <h2 class="h1 mb-0">84,695</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between p-md-1">
                                <div class="d-flex flex-row">
                                    <div class="align-self-center">
                                        <h2 class="h1 mb-0 me-4">$76,456.00</h2>
                                    </div>
                                    <div>
                                        <h4>Total Sales</h4>
                                        <p class="mb-0">Monthly Sales Amount</p>
                                    </div>
                                </div>
                                <div class="align-self-center">
                                    <i class="far fa-heart text-danger fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between p-md-1">
                                <div class="d-flex flex-row">
                                    <div class="align-self-center">
                                        <h2 class="h1 mb-0 me-4">$36,000.00</h2>
                                    </div>
                                    <div>
                                        <h4>Total Cost</h4>
                                        <p class="mb-0">Monthly Cost</p>
                                    </div>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-wallet text-success fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Section: Statistics with subtitles-->
        </div>
    </main>
@endsection
