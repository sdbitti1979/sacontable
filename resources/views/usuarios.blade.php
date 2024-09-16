@extends('app')

@section('content')
    <style>
        .container {
            max-width: 100% !important;
        }
    </style>
    <!-- Scripts -->

    <script type="text/javascript">
        var table;
        $(document).ready(function($) {

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                orderable: false,
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
                        data: 'rol_nombre',
                        name: 'rol_nombre'
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
                ],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
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
                Swal.fire({
                    title: "Confrimation!",
                    text: "¿Está seguro que quiere eliminar el registro?",
                    type: "warning",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Confirmar",
                    cancelButtonText: "Cancelar",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                }).then((isConfirm) => {
                    if (isConfirm.value === true) {
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
                                    Swal.fire({
                                        position: "top-end",
                                        icon: "success",
                                        title: "Usuario eliminado con éxito",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                } else {
                                    Swal.fire({
                                        position: "top-end",
                                        icon: "error",
                                        title: "Error: " + response.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            },
                            error: function(xhr) {
                                alert('Error al realizar la solicitud.');
                            }
                        });
                        return true;
                    }
                    return false;
                });

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
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Usuario creado con éxito",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    cerrarModal();


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
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Usuario editado con éxito",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    cerrarModal();


                }
            });
        }
    </script>

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
                                        <th>Rol</th>
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
    </main>
@endsection
