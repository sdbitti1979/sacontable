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
            $.ajax({
                url: "{{ route('asientos.getAsiento') }}", // Llama a la ruta para obtener el asiento
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Token CSRF para protección
                },
                data: {
                    id_asiento: "{{ $asientoId }}"
                },
                success: function(data) {
                    console.log(data[0].fecha);
                    // Verifica si data contiene elementos
                    if (data.length > 0) {
                        // Rellenar los campos del modal con los datos del primer registro (todos tienen la misma fecha, nro_asiento, y descripcion)
                        $('#fechaAsiento').val(data[0].fecha); // Usamos el primer elemento para los datos generales del asiento
                        $('#nroAsiento').val(data[0].nro_asiento);
                        $('#descripcionAsiento').val(data[0].descripcion);

                        // Limpiar la tabla antes de agregar las nuevas filas
                        $('#tablaCuentas tbody').empty();

                        // Recorrer los resultados y agregarlos a la tabla
                        $.each(data, function(index, cuenta) {
                            let fila = '<tr>' +
                                '<td>' + cuenta.idcuenta + '</td>' +
                                '<td>' + cuenta.nombre + '</td>' +
                                '<td>' + cuenta.debe + '</td>' +
                                '<td>' + cuenta.haber + '</td>' +
                                '</tr>';
                            $('#tablaCuentas tbody').append(fila);
                        });

                        // Mostrar el modal
                        $('#modalAsiento').modal('show');
                    } else {
                        alert('No se encontraron detalles del asiento.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener el asiento: ', error);
                    alert('Error al cargar el asiento');
                }
            });
        });
    </script>
@endsection

@section('modalBody')
    <div class="modal fade" id="ajaxModalAsientos" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ajaxModalLabel">Detalle del Asiento</h5>
                    <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                </div>
                <div class="modal-body">
                    <form>
                        <!-- Fecha y Descripción -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fechaasiento" class="form-label">Fecha</label>
                                <input type="text" id="fechaasiento" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="nroAsiento" class="form-label">Nro Asiento</label>
                                <input type="text" id="nroAsiento" class="form-control" disabled >
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcionAsiento" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcionAsiento" name="descripcionAsiento"
                                placeholder="Escribe la descripción del asiento..." disabled></textarea>
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

                </div>
            </div>
        </div>
    </div>
@endsection
