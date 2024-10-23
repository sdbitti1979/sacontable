<!-- resources/views/home.blade.php -->
@extends('app')

@section('script')
    <script type="text/javascript">
        var table;
        var myModal;
        $(document).ready(function($) {
            // Inicializar DataTable
            table = $('#asientos-table').DataTable({
                processing: true,
                serverSide: true,
                orderable: false,
                ordering: false,
                destroy: true,
                scrollY: '350px',
                ajax: {
                    url: "{{ route('asientos.lista') }}",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataSrc: json => {
                        if (Array.isArray(json.data)) {
                            return json.data.map(item => {
                                return [
                                    `<div onclick="detalleCuenta(${item.idasiento})" style="cursor:pointer">${item.fecha}</div>`,
                                    `<div onclick="detalleCuenta(${item.idasiento})" style="cursor:pointer">${item.nro_asiento}</div>`,
                                    `<div onclick="detalleCuenta(${item.idasiento})" style="cursor:pointer">${item.descripcion}</div>`,
                                    `<div onclick="detalleCuenta(${item.idasiento})" style="cursor:pointer">${item.usuario}</div>`
                                ];
                            });
                        } else {
                            console.error("La respuesta JSON no contiene un array en el campo 'data'.");
                            return [];
                        }
                    }
                }
                /*,
                                columns: [{
                                        data: 'fecha',
                                        name: 'fecha',
                                        orderable: false
                                    },
                                    {
                                        data: 'nro_asiento',
                                        name: 'nro_asiento',
                                        orderable: false
                                    },
                                    {
                                        data: 'descripcion',
                                        name: 'descripcion',
                                        orderable: false
                                    },
                                    {
                                        data: 'usuario_nombre',
                                        name: 'usuario.usuario',
                                        orderable: false
                                    } // Columna personalizada para el nombre del usuario
                                ]*/
                ,
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

            $('#ajaxModalAsientos').on('dialogclose', function(event) {
                alert('closed');
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
                    myModal = new bootstrap.Modal(document.getElementById('ajaxModalAsientos'));
                    //console.log(myModal);
                    myModal.show();
                },
                error: function(xhr) {
                    console.error('Error al cargar el modal');
                }
            });
        }

        function detalleCuenta(id_asiento) {
            $.ajax({
                url: "{{ route('asientos.detalleAsiento') }}?modal=true",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF para protección
                },
                data: {
                    id_asiento: id_asiento
                },
                success: function(response) {
                    //console.log(response);
                    // Cargar el contenido del modal en el contenedor
                    $('#modalContainer').html(response);


                    // Inicializar el modal y mostrarlo
                    myModal = new bootstrap.Modal(document.getElementById('ajaxModalAsientos'));
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

        /*function guardarAsiento() {
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



                    Swal.fire({
                        title: 'Éxito',
                        text: 'El registro se ha guardado correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            myModal.hide();
                            table.ajax.reload();
                            $('#ajaxModalAsientos').on('hidden.bs.modal', function() {
                                datepicker.close(); // Cierra el calendario de flatpickr cuando el modal se oculta
                            });
                        }
                    });





                }
            });
        }*/
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
                                        <th>Nro. Asiento</th>
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
