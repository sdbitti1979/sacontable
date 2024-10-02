<!-- resources/views/home.blade.php -->
@extends('app')

@section('script')
    <script type="text/javascript">
        var table;
        $(document).ready(function($) {
            // Inicializar DataTable
            table = $('#asientos-table').DataTable({
                processing: true,
                serverSide: true,
                orderable: false,
                ajax: {
                    url: "{{ route('asientos.lista') }}",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                columns: [{
                        data: 'fecha',
                        name: 'fecha',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'descripcion',
                        name: 'descripcion',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'usuario_nombre',
                        name: 'usuario.usuario',
                        orderable: false,
                        searchable: false
                    } // Columna personalizada para el nombre del usuario
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
        });


        function agregarAsiento() {
            $.ajax({
                url: "{{ route('asientos.agregarAsiento') }}?modal=true",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF para protección
                },
                success: function(response) {
                    //console.log(response);
                    // Cargar el contenido del modal en el contenedor
                    $('#modalContainer').html(response);


                    // Inicializar el modal y mostrarlo
                    var myModal = new bootstrap.Modal(document.getElementById('ajaxModalAsientos'));
                    //console.log(myModal);
                    myModal.show();
                },
                error: function(xhr) {
                    console.error('Error al cargar el modal');
                }
            });
        }

        setInterval(function() {
            table.ajax.reload();
        }, 30000);

        function guardarAsiento() {
            let data = {
                fecha: $("#fechaasiento").val(),
                descripcion: $("#descripcionAsiento").val()
            }

            var url = "{{ route('asientos.guardarAsiento') }}";

            $.ajax({
                url: url,
                type: "post",
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // El token CSRF desde el meta
                },
                //error: swalError,
                success: function(json) {




                    // O usando Bootstrap 5 directamente
                    var modalElement = document.getElementById('ajaxModalAsientos');
                    var modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    table.ajax.reload();
                    /*Swal.fire({
                        title: 'Éxito',
                        text: 'El registro se ha guardado correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });*/





                }
            });
        }
    </script>
@endsection
<!-- Modal Dinámico -->





@section('content')
    <main style="margin-top: 58px">
        <div class="container pt-4">
            <!-- Section: Main content -->
            <section class="mb-4">
                <div class="card">
                    <div class="card-header py-3">
                        <h5 class="mb-0 text-center"><strong>Asientos Contables</strong>
                            @if (in_array('USUARIOS.CREAR', $permissions ?? []))
                                <i class="fas fa-plus position-absolute end-0 me-3"
                                    style="font-size: 1.3rem; cursor: pointer;" onclick="agregarAsiento()"></i>
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="modalContainer"></div>
                        <div class="table-responsive">
                            <table id="asientos-table" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Descripción</th>
                                        <th>Usuario</th>
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
