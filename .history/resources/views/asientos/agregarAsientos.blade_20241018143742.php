@extends('app')

@section('style')
    <style>
        .asiento-table th, .asiento-table td {
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
    </style>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script>
        flatpickr("#fechaasiento", {
            locale: "es",
            enableTime: true,
            autoclose: true,
            dateFormat: "d/m/Y H:i:s",
            time_24hr: true
        });

        function agregarLinea() {
            // lógica para agregar una nueva fila en la tabla de cuentas
        }

        function eliminarLinea() {
            // lógica para eliminar la fila seleccionada
        }

        function guardarAsiento() {
            // lógica para guardar el asiento
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
                                <input type="text" id="fechaasiento" class="form-control" placeholder="dd/mm/yyyy HH:mm:ss">
                            </div>
                            <div class="col-md-6">
                                <label for="nroAsiento" class="form-label">Nro Asiento:</label>
                                <input type="text" id="nroAsiento" class="form-control" placeholder="Número de asiento">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcionAsiento" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcionAsiento" name="descripcionAsiento" placeholder="Escribe la descripción del asiento..."></textarea>
                        </div>

                        <!-- Selección de cuentas -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cuentaSelect" class="form-label">Cuenta:</label>
                                <select id="cuentaSelect" class="form-select">
                                    <option>Seleccionar cuenta</option>
                                    <option>Caja</option>
                                    <option>Banco Nación c/c</option>
                                    <!-- Otras cuentas -->
                                </select>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-outline-secondary mt-4" onclick="agregarLinea()">Agregar cuenta</button>
                                <button type="button" class="btn btn-outline-info mt-4">Plan de cuentas</button>
                                <button type="button" class="btn btn-outline-success mt-4">Agregar cuenta</button>
                            </div>
                        </div>

                        <!-- Monto y Tipo (Debe/Haber) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="monto" class="form-label">Monto</label>
                                <input type="text" id="monto" class="form-control" placeholder="Monto">
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="tipoMovimiento" id="debe" value="debe">
                                    <label class="form-check-label" for="debe">Debe</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipoMovimiento" id="haber" value="haber">
                                    <label class="form-check-label" for="haber">Haber</label>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de cuentas agregadas -->
                        <div class="table-container">
                            <table class="table table-bordered asiento-table">
                                <thead>
                                    <tr>
                                        <th>Cuenta</th>
                                        <th>Debe</th>
                                        <th>Haber</th>
                                        <th>Editar</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Ejemplos de líneas de asiento -->
                                    <tr>
                                        <td>Caja</td>
                                        <td>1000</td>
                                        <td></td>
                                        <td><button class="btn btn-sm btn-warning">Editar</button></td>
                                        <td><button class="btn btn-sm btn-danger" onclick="eliminarLinea()">Eliminar</button></td>
                                    </tr>
                                    <tr>
                                        <td>Banco Nación c/c</td>
                                        <td></td>
                                        <td>1000</td>
                                        <td><button class="btn btn-sm btn-warning">Editar</button></td>
                                        <td><button class="btn btn-sm btn-danger" onclick="eliminarLinea()">Eliminar</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="cerrarModal('ajaxModalAsientos')">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarAsiento()">Registrar asiento</button>
                </div>
            </div>
        </div>
    </div>
@endsection
