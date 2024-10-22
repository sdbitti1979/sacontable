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

        /* Estilo para las pestañas inactivas */
        .nav-tabs .nav-link:not(.active) {
            background-color: #f0f0f0;
            /* Cambia este color a tu preferencia */
            color: #555;
            /* Cambia el color del texto si es necesario */
        }

        /* Opcional: cambio de color en hover para las pestañas inactivas */
        .nav-tabs .nav-link:not(.active):hover {
            background-color: #e0e0e0;
            /* Color en hover */
            color: #333;
        }

        /* Estilo para la pestaña activa */
        .nav-tabs .nav-link.active {
            background-color: #ffffff;
            /* Color de fondo para la pestaña activa */
            color: #000;
            /* Color del texto de la pestaña activa */
            border-color: #dee2e6 #dee2e6 #fff;
            /* Para fusionar con el fondo de contenido */
        }
    </style>
@endsection
@section('script')
    <script type="text/javascript">
        var table;
        var permissions = {!! json_encode($permissions) !!};
        $(document).ready(function($) {


        });

        function inicializarDataTable(selector, url, datosAdicionales = {}) {
            table = $(selector).DataTable({
                processing: true,
                serverSide: true,
                orderable: false,
                responsive: true,
                scrollY: '200px',
                destroy: true,
                info: true,
                ordering: false,
                ajax: {
                    url: url,
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        // Agregar datos adicionales a la solicitud
                        return $.extend({}, d, datosAdicionales);
                    },
                    dataSrc: json => {
                        if (Array.isArray(json.data)) {
                            return json.data.map(item => {
                                return [
                                    `<div onclick="editarCuenta(${item.idcuenta})" style="cursor:pointer">${item.nombre}</div>`,
                                    `<div onclick="editarCuenta(${item.idcuenta})" style="cursor:pointer">${item.codigo}</div>`,
                                    `<div onclick="editarCuenta(${item.idcuenta})" style="cursor:pointer">${item.clasificacion}</div>`,
                                    `<div onclick="editarCuenta(${item.idcuenta})" style="cursor:pointer">${item.saldo_actual}</div>`,
                                    `<div onclick="editarCuenta(${item.idcuenta})" style="cursor:pointer">${item.cuenta_padre}</div>`,
                                    `<div onclick="editarCuenta(${item.idcuenta})" style="cursor:pointer">${item.usuario}</div>`,
                                    (permissions && permissions.includes('CUENTAS.ELIMINAR')) ?
                                    `<div>
                                    ${item.eliminada == 'NO' && item.utilizada == 'NO' ? `<i class="fas fa-trash-alt fa-lg" onclick="eliminarCuenta(${item.idcuenta})" style="cursor:pointer"></i>` : ``}
                                </div>` :
                                    `<div></div>`

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
        }

        function editarCuenta(idcuenta) {
            $.ajax({
                url: "{{ route('cuentas.editarCuenta') }}?modal=true",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF para protección
                },
                data: {
                    idcuenta: idcuenta
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
        // Uso de la función con datos adicionales
        function inicializarDataTableActivas() {

            if ($.fn.DataTable.isDataTable('#cuentas-table')) {
                $('#cuentas-table').DataTable().destroy();
            }
            inicializarDataTable(
                '#cuentas-table',
                "{{ route('cuentas.lista') }}", {
                    solapa: 'activas'
                } // Datos adicionales
            );
        }

        function inicializarDataTableInactivas() {

            if ($.fn.DataTable.isDataTable('#cuentas-table')) {
                $('#cuentas-table-inactivas').DataTable().destroy();
            }
            inicializarDataTable(
                '#cuentas-table-inactivas',
                "{{ route('cuentas.lista') }}", {
                    solapa: 'inactivas'
                } // Datos adicionales
            );
        }

        inicializarDataTableActivas();

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
                url: "{{ route('cuentas.cuentaUtilizada') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF para protección
                },
                data: {
                    idcuenta: idcuenta
                },
                success: function(response) {
                    if (response.msg !== 'Ok') {
                        Swal.fire({
                            text: response.msg,
                            icon: response.type,
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        $.ajax({
                            url: "{{ route('cuentas.eliminarCuenta') }}",
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content') // Token CSRF para protección
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

                },
                error: function(xhr) {
                    console.error('Error al cargar el modal');
                }
            });


        }
    </script>
@endsection
@section('content')
    <main class="mt-5">
        <div class="container-fluid px-3 px-md-5 pt-4">
            <ul class="nav nav-tabs" id="mainTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="plan-cuentas-tab" data-bs-toggle="tab" data-bs-target="#plan-cuentas"
                        type="button" role="tab" aria-controls="plan-cuentas" aria-selected="true"
                        onclick="inicializarDataTableActivas()">Cuentas Activas</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="plan-cuentas-inactivas-tab" data-bs-toggle="tab"
                        data-bs-target="#plan-cuentas-inactivas" type="button" role="tab" aria-controls="otra-seccion"
                        aria-selected="false" onclick="inicializarDataTableInactivas()">Cuentas Eliminadas</button>
                </li>
                <!-- Puedes añadir más solapas aquí -->
            </ul>
            <div class="tab-content" id="mainTabsContent">
                <!-- Contenido de la solapa "Plan de Cuentas" -->
                <div class="tab-pane fade show active" id="plan-cuentas" role="tabpanel" aria-labelledby="plan-cuentas-tab">
                    <section class="mb-4">
                        <div class="card">
                            <div
                                class="card-header py-3 d-flex justify-content-center align-items-center position-relative">
                                <h5 class="mb-0"><strong>Plan de Cuentas</strong></h5>
                                @if (in_array('CUENTAS.CREAR', $permissions ?? []))
                                    <i class="fas fa-plus position-absolute end-0 me-3"
                                        style="font-size: 1.3rem; cursor: pointer;" onclick="agregarCuenta()"></i>
                                @endif
                            </div>
                            <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
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
                                                <th>Usuario</th>
                                                @if (in_array('CUENTAS.ELIMINAR', $permissions ?? []))
                                                    <th>Eliminar</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Contenido de la tabla -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="tab-pane fade show" id="plan-cuentas-inactivas" role="tabpanel"
                    aria-labelledby="plan-cuentas-tab">
                    <section class="mb-4">
                        <div class="card">
                            <div
                                class="card-header py-3 d-flex justify-content-center align-items-center position-relative">
                                <h5 class="mb-0"><strong>Plan de Cuentas</strong></h5>

                            </div>
                            <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
                                <div id="modalContainer"></div>
                                <div class="table-responsive">
                                    <table id="cuentas-table-inactivas" class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Código</th>
                                                <th>Clasificación</th>
                                                <th>Saldo Actual</th>
                                                <th>Padre</th>
                                                <th>Usuario</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Contenido de la tabla -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>
@endsection
