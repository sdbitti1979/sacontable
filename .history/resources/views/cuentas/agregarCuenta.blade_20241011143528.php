@extends('app')
@section('style')
    <style>
        .form-check {
            padding-top: 2em;
        }

        .ui-autocomplete {
            z-index: 10000 !important;
            max-height: 200px;
            overflow-y: auto;
            background-color: white;
            /* Asegura que el fondo sea visible */
        }
    </style>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script type="text/javascript">
        $(document).ready(function($) {
            $('.solo-numeros').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $('.input-moneda').on('input', function() {
                let valor = this.value.replace(/[^0-9.]/g, ''); // Permite solo números y punto decimal
                let partes = valor.split('.'); // Separa la parte entera y decimal

                // Asegura que solo haya una parte decimal
                if (partes.length > 2) {
                    valor = partes[0] + '.' + partes[1];
                }

                // Formato de moneda (opcional, agrega comas)
                let entero = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                let decimal = partes[1] ? '.' + partes[1].substring(0, 2) : '';

                this.value = entero + decimal;
            });
            /*$("#clasificacion").on("change", function() {
                var nombre = $("#clasificacion :selected").text()
                    .trim(); // Obtiene el valor seleccionado del select

                $.ajax({
                    url: "{{ route('cuentas.getCatNombres') }}", // URL del servidor donde se hará la solicitud
                    type: 'post', // Tipo de solicitud (GET o POST)
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Token CSRF para protección
                    },
                    dataType: 'json', // Tipo de datos que se espera recibir (JSON)
                    data: {
                        nombre: nombre
                    }, // Datos que se enviarán al servidor (en este caso, el id seleccionado)
                    success: function(response) {
                        // Manejar la respuesta recibida (response es el JSON retornado)
                        //console.log(response);

                        var $select = $('#catnombre'); // El select que vamos a llenar
                        $select.empty(); // Limpiar las opciones actuales

                        // Usar map para crear las opciones y agregarlas al select
                        const opciones = response.map(item =>
                            '<option value="' + item.id_catnombres + '">' + item.nombre +
                            '</option>'
                        );

                        // Insertar las opciones en el select
                        $select.append(opciones.join(''));

                    },
                    error: function(xhr, status, error) {
                        console.error('Error al realizar la solicitud AJAX:', error);
                    }
                });
            });*/

            $("#codigo").on("blur", function() {
                $.ajax({
                    url: "{{ route('cuentas.getClasificaciones') }}", // Ruta al método en el controlador
                    dataType: "json",
                    type: "post",
                    data: {
                        descripcion: $(this).val() // Pasar el término de búsqueda como parámetro
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {

                        $("#clasificacion").val(data["datos"]["nombre"]);

                    }
                });
            });
            /*$("#codigo").autocomplete({
                autoFocus: true,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('cuentas.getClasificaciones') }}", // Ruta al método en el controlador
                        dataType: "json",
                        type: "GET",
                        data: {
                            descripcion: request.term // Pasar el término de búsqueda como parámetro
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            //console.log(data); // Verifica los datos en la consola
                            response($.map(data, function(item) {
                                return {
                                    label: item.label,
                                    value: item.value,
                                    id: item.id
                                };
                            }));
                        }
                    });
                },
                minLength: 1,
                select: function(event, ui) {
                    console.log(ui); // Verifica los datos seleccionados en la consola
                    //$("#cuentaPadreId").val(ui.item.id);
                    //$("#cuentaPadre").val(ui.item.value);
                    return false;
                }
            });*/

            $("#cuentaPadre").autocomplete({
                autoFocus: true,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('cuentas.getCuentas') }}", // Ruta al método en el controlador
                        dataType: "json",
                        type: "GET",
                        data: {
                            descripcion: request
                                .term // Pasar el término de búsqueda como parámetro
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            //console.log(data); // Verifica los datos en la consola
                            response($.map(data, function(item) {
                                return {
                                    label: item.label,
                                    value: item.value,
                                    id: item.id
                                };
                            }));
                        }
                    });
                },
                minLength: 1,
                select: function(event, ui) {
                    //console.log(ui); // Verifica los datos seleccionados en la consola
                    $("#cuentaPadreId").val(ui.item.id);
                    $("#cuentaPadre").val(ui.item.value);
                    return false;
                }
            });

            $("#nombre").on("blur", function() {
                var data = $(this).val();
                $.ajax({
                    url: "{{ route('cuentas.verificarNombre') }}", // Ruta al método en el controlador
                    dataType: "json",
                    type: "post",
                    data: {
                        descripcion: data
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.vacio == false) {
                            $("#spnombre").html("La cuenta ya existe");
                        } else {
                            $("#spnombre").html("");
                        }
                    }
                });

            });

            /*$("#codigo").on("blur", function() {
                var data = $(this).val();
                $.ajax({
                    url: "{{ route('cuentas.verificarCodigo') }}", // Ruta al método en el controlador
                    dataType: "json",
                    type: "post",
                    data: {
                        descripcion: data
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.vacio == false) {
                            $("#spcodigo").html("El código ya existe");
                        } else {
                            $("#spcodigo").html("");
                        }
                    }
                });
            });*/

        });
        flatpickr("#fechaasiento", {
            locale: "es",
            autoclose: true,
            dateFormat: "d/m/Y"
        });
        /*document.addEventListener('click', function(event) {
            if (!event.target.closest('#fechaasiento')) {
                datepicker.close(); // Cerrar el calendario
            }
        });*/
        //activarFiltro('cuentaPadre', '{{ route('cuentas.getCuentas') }}');

        function guardarCuenta() {
            let data = {
                nombre: $("#nombre").val(),
                codigo: $("#codigo").val(),
                clasificacion: $("#clasificacion").val(),
                catnombre: $("#catnombre").val(),
                saldoActual: $("#saldoActual").val(),
                recibeSaldo: ($("#recibeSaldo").is(':checked') ? 'T' : 'F'),
                cuentaPadre: $("#cuentaPadreId").val()
            }

            var url = "{{ route('cuentas.guardarCuenta') }}";

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
                                datepicker
                                    .close(); // Cierra el calendario de flatpickr cuando el modal se oculta
                            });
                        }
                    });





                }
            });
        }
    </script>
@endsection
@section('modalBody')
    <div class="modal fade" id="ajaxModalCuentas" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ajaxModalLabel">Alta de Cuenta</h5>
                    <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                </div>
                <div class="modal-body">
                    <form>

                        <div class="col-md-12 mb-3">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" autofocus>
                            <span id="spnombre" style="color:red"></span>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="codigo" class="form-label">Nro. Cuenta</label>
                                <input type="text" id="codigo" name="codigo" class="form-control solo-numeros">
                                <span id="spcodigo" style="color:red"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="clasificacion" class="form-label">Clasificación</label>
                                <input type="text" id="clasificacion" name="clasificacion" class="form-control" disabled>
                            </div>


                            <div class="col-md-3 mb-3">
                                <label for="saldoActual" class="form-label">Saldo Actual</label>
                                <input type="text" id="saldoActual" name="saldoActual" class="form-control input-moneda">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="cuentaPadre" class="form-label">Cuenta Padre</label>
                                <input type="hidden" id="cuentaPadreId" name="cuentaPadreId">
                                <input type="text" id="cuentaPadre" style="position: relative; z-index: 9999;"
                                    name="cuentaPadre" class="form-control" placeholder="Escribe un nombre...">

                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        onclick="cerrarModal('ajaxModalCuentas')">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarCuenta()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
@endsection
