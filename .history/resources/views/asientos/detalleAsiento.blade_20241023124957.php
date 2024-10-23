@extends('app')

@section('style')
    <style>
        .modal-content {
            width: 1000px;
            margin-left: -15em;
            margin-top: -1.5em;
        }

        .asiento-table th,
        .asiento-table td {
            text-align: center;
        }

        .asiento-table {
            width: 100%;
            margin-top: 20px;
        }

        .asiento-table th {
            background-color: #f8f9fa;
        }

        .table-container {
            max-height: 200px;
            overflow-y: auto;
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
            $("#cuentaIn").autocomplete({
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
                            console.log(data);
                            if (data.length > 0) {
                                response($.map(data, function(item) {
                                    //console.log("Item procesado:", item);
                                    return {
                                        label: item.descripcion || item.label,
                                        value: item.descripcion || item.value,
                                        id: item.id
                                    };
                                }));
                            } else {
                                $("#cuentaIn").val("s/c");
                            }
                        }
                    });
                },
                minLength: 1,
                select: function(event, ui) {
                    //console.log(ui); // Verifica si esto se ejecuta
                    if (ui && ui.item) {
                        $("#cuentaId").val(ui.item.id);
                        $("#cuentaIn").val(ui.item.value);
                    }
                    return false;
                }
            });

            $('.input-moneda').on('keyup', function() {
                // Permitir solo números y una coma o un punto
                let valor = this.value.replace(/[^0-9,]/g, '');

                // Asegurar que solo haya una coma decimal
                let partes = valor.split(',');
                if (partes.length > 2) {
                    valor = partes[0] + ',' + partes[1]; // Mantener solo la primera parte decimal
                }

                // Agregar puntos como separador de miles solo a la parte entera
                let entero = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                let decimal = partes[1] !== undefined ? ',' + partes[1].substring(0, 2) :
                    ''; // Limitar a dos decimales

                // Actualizar el valor en el campo de entrada
                this.value = entero + decimal;
            });
        });


        /*const fp = flatpickr("#fechaasiento", {
            locale: "es",
            enableTime: true,
            autoclose: true,
            dateFormat: "d/m/Y H:i:s",
            time_24hr: true
        });

        // Prevenir la apertura de Flatpickr cuando no es deseado
        document.querySelector("#fechaasiento").addEventListener('focus', function(event) {
            if (Swal.isVisible()) {
                event.stopPropagation(); // Detener la propagación del evento si el SweetAlert está visible
            }
        });*/

        function convertirFechaAFormato(fecha) {
            const partes = fecha.split('/');
            // Asegúrate de que esté en el formato "DD/MM/YYYY"
            if (partes.length === 3) {
                return `${partes[2]}-${partes[1]}-${partes[0]}`; // Devuelve en formato "YYYY-MM-DD"
            }
            return fecha; // Si no se puede dividir bien, retorna la fecha original
        }

        function agregarLinea() {
            // lógica para agregar una nueva fila en la tabla de cuentas
        }

        function eliminarLinea() {
            // lógica para eliminar la fila seleccionada
        }

        function guardarAsiento() {
            // Recoger los datos del formulario
            //const fecha = $('#fechaasiento').val();
            const descripcion = $('#descripcionAsiento').val();
            const nroAsiento = $('#nroAsiento').val();

            let fecha = $('#fechaasiento').val();  // Valor de la fecha del input
            fecha = convertirFechaAFormato(fecha);
            // Recoger los datos de la tabla dinámica
            let cuentas = [];

            // Verificar si hay filas en el tbody de la tabla
            //console.log($('#tablaCuentas tbody tr').length); // Verifica cuántas filas tienes en la tabla

            $('#tablaCuentas tbody tr').each(function() {
                // Acceder a todas las celdas de la fila
                const celdas = $(this).children('td');

                const cuentaId = celdas.eq(0).text(); // Acceder a la primera celda (ID de la cuenta)
                const debe = celdas.eq(2).text(); // Acceder a la tercera celda (Monto en "Debe")
                const haber = celdas.eq(3).text(); // Acceder a la cuarta celda (Monto en "Haber")

                console.log(cuentaId, debe, haber); // Verifica que los valores están siendo recogidos

                cuentas.push({
                    cuenta_id: cuentaId.trim(), // Quitar espacios en blanco si es necesario
                    debe: debe.trim() || 0,
                    haber: haber.trim() || 0
                });
            });

            // Verificar el contenido del array de cuentas
            //console.log(cuentas);

            // Validar que se haya agregado al menos una cuenta
            if (cuentas.length === 0) {
                alert("Debe agregar al menos una cuenta.");
                return;
            }

            // Validar que los campos requeridos no estén vacíos
            if (!fecha || !descripcion || !nroAsiento) {
                alert("Por favor, complete todos los campos requeridos.");
                return;
            }

            // Realizar la solicitud AJAX POST
            $.ajax({
                url: "{{ route('asientos.registrarAsiento') }}", // Ruta al método del controlador Laravel
                type: "POST",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}', // Token CSRF para la seguridad
                    fecha: fecha,
                    descripcion: descripcion,
                    nro_asiento: nroAsiento,
                    cuentas: cuentas
                },
                success: function(response) {
                    // Si el registro es exitoso, mostrar un mensaje
                    Swal.fire({
                        text: 'Asiento registrado con éxito.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });
                    // Limpiar la tabla y el formulario si es necesario
                    $('#tablaCuentas tbody').empty();
                    $('#descripcionAsiento').val('');
                    $('#fechaasiento').val('');
                },
                error: function(xhr, status, error) {
                    // Si hay un error, mostrar un mensaje
                    Swal.fire({
                        text: 'Ocurrió un error al registrar el asiento.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    console.error(xhr.responseText);
                }
            });
        }


        function normalizarMonto(monto) {
            // Eliminar los separadores de miles (comas o puntos, según el formato regional)
            // y luego convertir la coma decimal en punto
            return parseFloat(monto.replace(/\./g, '').replace(',', '.'));
        }

        const agregarCuentaBtn = document.getElementById('agregarCuenta');
        const tablaCuentas = document.getElementById('tablaCuentas');
        const verificarBalanceBtn = document.getElementById('verificarBalance');



        function agregarCuenta() {
            // Obtener los valores de los inputs
            let cuentaId = document.getElementById('cuentaId').value;
            let cuenta = document.getElementById('cuentaIn').value;
            let monto = document.getElementById('monto').value;
            let debeHaber = document.querySelector('input[name="debeHaber"]:checked')?.value;

            // Validar los campos
            if (cuenta === '' || monto === '') {
                alert('Por favor completa todos los campos');
                return;
            }
            monto = normalizarMonto(monto);
            //const cuentaExistente = existeCuenta(cuenta);

            const resultado = existeCuenta(cuenta, debeHaber);

            if (resultado.existe && resultado.tipoCoincide) {
                // Actualizar la cuenta existente
                const celdaDebe = resultado.fila.getElementsByTagName('td')[2];
                const celdaHaber = resultado.fila.getElementsByTagName('td')[3];

                if (debeHaber === "Debe") {
                    celdaDebe.innerText = parseFloat(celdaDebe.innerText || 0) + monto;
                } else {
                    celdaHaber.innerText = parseFloat(celdaHaber.innerText || 0) + monto;
                }
            } else {


                // Crear una nueva fila
                let fila = document.createElement('tr');

                // Crear columnas
                let columnaCuentaId = document.createElement('td');
                columnaCuentaId.innerText = cuentaId;

                let columnaCuenta = document.createElement('td');
                columnaCuenta.innerText = cuenta;

                let columnaDebe = document.createElement('td');
                let columnaHaber = document.createElement('td');

                if (debeHaber === 'Debe') {
                    columnaDebe.innerText = monto;
                    columnaHaber.innerText = '';
                } else {
                    columnaDebe.innerText = '';
                    columnaHaber.innerText = monto;
                }

                let columnaEditar = document.createElement('td');
                let btnEditar = document.createElement('button');
                btnEditar.innerText = 'Editar';
                btnEditar.classList.add('btn', 'btn-warning');
                btnEditar.onclick = function() {
                    editarFila(this);
                };
                columnaEditar.appendChild(btnEditar);

                let columnaEliminar = document.createElement('td');
                let btnEliminar = document.createElement('button');
                btnEliminar.innerText = 'Eliminar';
                btnEliminar.classList.add('btn', 'btn-danger');
                btnEliminar.onclick = function() {
                    eliminarFila(this);
                };
                columnaEliminar.appendChild(btnEliminar);

                // Agregar columnas a la fila
                fila.appendChild(columnaCuentaId);
                fila.appendChild(columnaCuenta);
                fila.appendChild(columnaDebe);
                fila.appendChild(columnaHaber);
                fila.appendChild(columnaEditar);
                fila.appendChild(columnaEliminar);

                // Agregar la fila a la tabla
                //document.getElementById('tablaCuentas').appendChild(fila);
                let tablaCuentas = document.getElementById('tablaCuentas');
                let tbody = tablaCuentas.querySelector('tbody');
                tbody.appendChild(fila);
            }

            // Limpiar los campos
            document.getElementById('cuentaId').value = '';
            document.getElementById('cuentaIn').value = '';
            document.getElementById('monto').value = '';
            document.getElementById('debe').checked = true;

        }

        // Función para verificar el balance entre "Debe" y "Haber"
        function verificarBalance() {
            event.preventDefault();

            let totalDebe = 0;
            let totalHaber = 0;

            const filas = tablaCuentas.getElementsByTagName('tr');

            // Forzar el cierre del calendario Flatpickr
            const flatpickrInstance = document.querySelector("#fechaasiento")._flatpickr;
            if (flatpickrInstance) {
                flatpickrInstance.close(); // Cerrar el calendario si está abierto
            }
            document.activeElement.blur();

            for (let fila of filas) {
                const celdas = fila.getElementsByTagName('td');

                // Verificar si la fila tiene al menos 3 celdas (Cuenta, Debe, Haber)
                if (celdas.length >= 3) {
                    const celdaDebe = celdas[2].innerText.replace(",", "."); // Segunda celda es "Debe"
                    const celdaHaber = celdas[3].innerText.replace(",", "."); // Tercera celda es "Haber"

                    totalDebe += parseFloat(celdaDebe) || 0; // Sumar "Debe", o 0 si no es un número
                    totalHaber += parseFloat(celdaHaber) || 0; // Sumar "Haber", o 0 si no es un número
                }
            }

            // Redondear los totales a dos decimales
            totalDebe = totalDebe.toFixed(2);
            totalHaber = totalHaber.toFixed(2);

            setTimeout(() => {
                // Verificar si el total de "Debe" es igual al total de "Haber"
                if (totalDebe === totalHaber) {
                    Swal.fire({
                        text: `El balance es correcto. Total Debe: ${totalDebe}, Total Haber: ${totalHaber}`,
                        icon: "success",
                        confirmButtonText: 'Aceptar',
                        backdrop: false,
                        focusConfirm: false
                    });
                    return false;
                } else {
                    Swal.fire({
                        text: `El balance NO es correcto. Total Debe: ${totalDebe}, Total Haber: ${totalHaber}`,
                        icon: "warning",
                        confirmButtonText: 'Aceptar',
                        backdrop: false,
                        focusConfirm: false
                    });
                    return false;
                }
            }, 0);
        }


        agregarCuentaBtn.addEventListener('click', agregarCuenta);

        // Agregar evento al botón "Verificar Balance"
        verificarBalanceBtn.addEventListener('click', verificarBalance);

        function eliminarFila(boton) {
            // Eliminar la fila que contiene el botón
            let fila = boton.parentNode.parentNode;
            fila.remove();
        }

        function editarFila(boton) {
            // Obtener la fila y sus celdas
            let fila = boton.parentNode.parentNode;
            let cuentaId = fila.cells[0].innerText;
            let cuenta = fila.cells[1].innerText;
            let debe = fila.cells[2].innerText;
            let haber = fila.cells[3].innerText;

            // Asignar los valores a los inputs para edición
            document.getElementById('cuentaId').value = cuentaId;
            document.getElementById('cuentaIn').value = cuenta;
            document.getElementById('monto').value = debe || haber;
            if (debe !== '') {
                document.getElementById('debe').checked = true;
            } else {
                document.getElementById('haber').checked = true;
            }

            // Eliminar la fila para poder crearla nuevamente con los cambios
            fila.remove();
        }

        function existeCuenta(nombreCuenta, tipo) {
            const filas = tablaCuentas.getElementsByTagName('tr');

            for (let fila of filas) {
                const celdas = fila.getElementsByTagName('td');
                if (celdas.length >= 4) { // Asegúrate de que haya suficientes celdas en la fila
                    const celdaCuenta = celdas[1]; // Asumiendo que la cuenta está en la segunda columna
                    const celdaDebe = celdas[2]; // "Debe" en la tercera columna
                    const celdaHaber = celdas[3]; // "Haber" en la cuarta columna

                    if (celdaCuenta && celdaCuenta.innerText.trim() === nombreCuenta.trim()) {
                        const valorDebe = parseFloat(celdaDebe.innerText.replace(",", ".") ||
                            0); // Normaliza el valor de "Debe"
                        const valorHaber = parseFloat(celdaHaber.innerText.replace(",", ".") ||
                            0); // Normaliza el valor de "Haber"

                        // Verificar si el tipo de cuenta coincide con el valor
                        if (tipo === "Debe" && valorDebe > 0) {
                            return {
                                existe: true,
                                tipoCoincide: true,
                                fila
                            }; // Existe y es "Debe"
                        } else if (tipo === "Haber" && valorHaber > 0) {
                            return {
                                existe: true,
                                tipoCoincide: true,
                                fila
                            }; // Existe y es "Haber"
                        } else {
                            return {
                                existe: true,
                                tipoCoincide: false
                            }; // Existe pero el tipo no coincide
                        }
                    }
                }
            }
            return {
                existe: false
            }; // No existe
        }
    </script>
@endsection

@section('modalBody')
    <div class="modal fade" id="ajaxModalAsientos" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ajaxModalLabel">Registrar Asiento</h5>
                    <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                </div>
                <div class="modal-body">
                    <form>
                        <!-- Fecha y Descripción -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fechaasiento" class="form-label">Fecha:</label>
                                <input type="text" id="fechaasiento" class="form-control" disabled value="{{ $fecha }}">
                            </div>
                            <div class="col-md-6">
                                <label for="nroAsiento" class="form-label">Nro Asiento:</label>
                                <input type="text" id="nroAsiento" class="form-control" disabled
                                    placeholder="Número de asiento" value="{{ $nro_asiento }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcionAsiento" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcionAsiento" name="descripcionAsiento"
                                placeholder="Escribe la descripción del asiento..." disabled></textarea>
                        </div>

                        <!-- Selección de cuentas -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cuentaSelect" class="form-label">Cuenta</label>
                                <input type="hidden" id="cuentaId" name="cuentaId">
                                <input type="text" id="cuentaIn" name="cuenta" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="monto" class="form-label">Monto</label>
                                <input type="text" id="monto" class="form-control input-moneda" placeholder="Monto">
                            </div>
                        </div>

                        <!-- Monto y Tipo (Debe/Haber) -->
                        <div class="row mb-3">

                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="debeHaber" id="debe"
                                        value="Debe" checked>
                                    <label class="form-check-label" for="debe">Debe</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="debeHaber" id="haber"
                                        value="Haber">
                                    <label class="form-check-label" for="haber">Haber</label>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <!--<button type="button" class="btn btn-outline-secondary mt-4"
                                                                                                                                        onclick="agregarLinea()">Agregar cuenta</button>
                                                                                                                                    <button type="button" class="btn btn-outline-info mt-4">Plan de cuentas</button>-->
                                <button id="verificarBalance" class="btn btn-primary mt-2">Verificar Balance</button>
                                <button type="button" class="btn btn-outline-success mt-4" id="agregarCuenta">Agregar
                                    cuenta</button>
                            </div>
                        </div>

                        <!-- Tabla de cuentas agregadas -->
                        <div class="table-container">
                            <table class="table table-bordered asiento-table" id="tablaCuentas">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cuenta</th>
                                        <th>Debe</th>
                                        <th>Haber</th>
                                        <th>Editar</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        onclick="cerrarModal('ajaxModalAsientos')">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarAsiento()">Registrar asiento</button>
                </div>
            </div>
        </div>
    </div>
@endsection
