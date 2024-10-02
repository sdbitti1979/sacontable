@extends('app')

@section('style')
    <style>
        .container {
            max-width: 80% !important;
        }

        #rolesTabla tr.row_selected {
            background-color: #B5CCD2;
            opacity: 0.95;
            font-weight: bold;
        }
    </style>
@endsection
@section('script')
    <script type="text/javascript">
        var rolesTabla;
        $(document).ready(function($) {

            rolesTabla = $('#rolesTabla').DataTable({
                processing: true,
                serverSide: true,
                orderable: false,
                ajax: {
                    url: "{{ route('roles.data') }}", // Ruta del controlador
                    type: "POST", // Cambiar el método a POST
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Agregar el token CSRF
                    }
                },
                columns: [{
                        data: 'idrol',
                        name: 'idrol',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'rol',
                        name: 'descripcion',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'edit',
                        name: 'edit',
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

            $('#rolesTabla').on('click', '.edit', function() {
                var id = $(this).data('id');
                if (id === undefined) {
                    console.error('ID no definido');
                    return;
                }

                $.ajax({
                    url: "{{ route('roles.editarRol') }}",
                    type: "POST",
                    data: {
                        rolid: id,
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
    </script>
@endsection
@section('content')
    <div id="modalContainer"></div>
    <main style="margin-top: 58px">
        <div class="container pt-4">
            <!-- Section: Main chart -->
            <section class="mb-4">
                <div class="card">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <button class="btn btn-secondary" onclick="history.back()">
                            <i class="fas fa-arrow-left"></i> Volver
                        </button>
                        <h5 class="mb-0 text-center"><strong>Roles</strong>
                            <!-- <i class="fas fa-user-plus position-absolute end-0 me-3"
                                                        style="font-size: 1.3rem; cursor: pointer;" onclick="agregarRol()"></i>-->
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
                            <table id="rolesTabla" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Rol</th>
                                        <th>Editar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </section>
        </div>
    </main>
@endsection
