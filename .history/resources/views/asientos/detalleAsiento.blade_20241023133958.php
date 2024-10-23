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
                url: '/asientos/' + asientoId, // Llama a la ruta para obtener el asiento
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Rellenar los campos del modal con los datos obtenidos
                    $('#fechaAsiento').val(data.fecha);
                    $('#nroAsiento').val(data.nro_asiento);
                    $('#descripcionAsiento').val(data.descripcion);

                    // Limpiar la tabla antes de agregar las nuevas filas
                    $('#tablaCuentas tbody').empty();

                    // Recorrer las cuentas y agregarlas a la tabla
                    $.each(data.cuentas, function(index, cuenta) {
                        let fila = '<tr>' +
                            '<td>' + cuenta.cuenta_id + '</td>' +
                            '<td>' + cuenta.nombre + '</td>' +
                            '<td>' + cuenta.debe + '</td>' +
                            '<td>' + cuenta.haber + '</td>' +
                            '</tr>';
                        $('#tablaCuentas tbody').append(fila);
                    });

                    // Mostrar el modal
                    $('#modalAsiento').modal('show');
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
                                <label for="fechaasiento" class="form-label">Fecha:</label>
                                <input type="text" id="fechaasiento" class="form-control" disabled
                                    value="{{ $asiento['fecha'] }}">
                            </div>
                            <div class="col-md-6">
                                <label for="nroAsiento" class="form-label">Nro Asiento:</label>
                                <input type="text" id="nroAsiento" class="form-control" disabled
                                    placeholder="Número de asiento" value="{{ $asiento['nro_asiento'] }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcionAsiento" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcionAsiento" name="descripcionAsiento"
                                placeholder="Escribe la descripción del asiento..." disabled>{{ $asiento['descripcion'] }}</textarea>
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
