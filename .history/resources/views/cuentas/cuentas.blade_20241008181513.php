@extends('app')
@section('style')
    <style>
        /*.container-xxl{
                    max-width: 1500px !important;
                    width: 1320px;
                    margin-left: 0em !important;
                }*/

        .highlight {
            background-color: yellow;
            /* Cambia el color según prefieras */
            color: black;
        }
    </style>
@endsection
@section('script')
    <script type="text/javascript">
        var table;
        $(document).ready(function($) {
            table = $('#cuentas-table').DataTable({
                processing: true,
                serverSide: true,
                orderable: false,
                responsive: true,
                scrollY: '350px',
                "createdRow": function(row, data, dataIndex) {
                    // Aplica la condición directamente en cada fila al momento de crearse
                    // Por ejemplo, si quieres resaltar las filas donde el valor de la primera columna es mayor a 100
                    if (data[6] == 'SI') { // Cambia "data[0]" a la columna adecuada
                        $(row).addClass('highlight');
                    }
                },
                ajax: {
                    url: "{{ route('cuentas.lista') }}",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    dataSrc: json => {
                        //console.log(json);
                        if (Array.isArray(json.data)) {
                            return json.data.map(item => {

                                return [
                                    `<div>${item.nombre}</div>`,
                                    `<div>${item.codigo}</div>`,
                                    `<div>${item.clasificacion}</div>`,
                                    `<div>${item.saldo_actual}</div>`,
                                    `<div>${item.cuenta_padre}</div>`,
                                    `<div>${item.utilizada}</div>`,
                                    `<div>${item.eliminada}</div>`,
                                    `<div>${item.usuario}</div>`,
                                    `<div>${item.recibe_saldo}</div>`,
                                    `<div>
                                    ${item.eliminada == 'NO' && item.utilizada == 'NO'? `<i class="fas fa-trash-alt fa-lg" onclick="eliminarCuenta(${item.idcuenta})"></i>`:``}
                                    </div>`
                                ];
                            });
                        } else {
                            console.error("La respuesta JSON no contiene un array en el campo 'data'.");
                            return [];
                        }
                    }
                },

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

        function agregarCuenta() {
            $.ajax({
                url: "{{ route('cuentas.agregarCuenta') }}?modal=true",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF para protección
                },
                success: function(response) {
                    //console.log(response);
                    // Cargar el contenido del modal en el contenedor
                    $('#modalContainer').html(response);


                    // Inicializar el modal y mostrarlo
                    myModal = new bootstrap.Modal(document.getElementById('ajaxModalCuentas'));
                    //console.log(myModal);
                    myModal.show();
                },
                error: function(xhr) {
                    console.error('Error al cargar el modal');
                }
            });
        }

        function eliminarCuenta(idcuenta) {
            $.ajax({
                url: "{{ route('cuentas.eliminarCuenta') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF para protección
                },
                data: {
                    idcuenta: idcuenta
                },
                success: function(response) {

                    table.ajax.reload();
                    Swal.fire({
                        text: response.msg,
                        icon: response.type,
                        confirmButtonText: 'Aceptar'
                    });
                },
                error: function(xhr) {
                    console.error('Error al cargar el modal');
                }
            });
        }
    </script>
@endsection
@section('content')
    <main style="margin-top: 58px">
        <div class="container-xxl pt-4">
            <!-- Section: Main content -->
            <section class="mb-4">
                <div class="card">
                    <div class="card-header py-3">
                        <h5 class="mb-0 text-center"><strong>Plan de Cuentas</strong>
                            @if (in_array('CUENTAS.CREAR', $permissions ?? []))
                                <i class="fas fa-plus position-absolute end-0 me-3"
                                    style="font-size: 1.3rem; cursor: pointer;" onclick="agregarCuenta()"></i>
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="modalContainer"></div>
                        <div class="table-responsive">
                            <table id="cuentas-table" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Código</th>
                                        <th>Clasificación</th>
                                        <th>Saldo Actual</th>
                                        <th>Padre</th>
                                        <th>Utilizada</th>
                                        <th>Eliminada</th>
                                        <th>Usuario</th>
                                        <th>Recibe Saldo</th>
                                        <th>Eliminar</th>
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
