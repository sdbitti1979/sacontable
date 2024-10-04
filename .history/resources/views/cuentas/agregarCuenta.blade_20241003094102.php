@extends('app')
@section('style')
    <style>

    </style>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script>
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
                            <input type="text" id="nombre" name="nombre" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="codigo" class="form-label">Código</label>
                                <input type="text" id="codigo" name="codigo" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="clasificacion" class="form-label">Clasificación</label>
                                <select class="form-control" name="clasificacion" id="clasificacion">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="saldoActual" class="form-label">Saldo Actual</label>
                                <input type="text" id="saldoActual" name="saldoActual" class="form-control">
                            </div>
                            <div class="col-md-2 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" id="recibeSaldo" name="recibeSaldo" class="form-check-input">
                                    <label for="recibeSaldo" class="form-check-label">Recibe Saldo</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cuentaPadrea" class="form-label">Cuenta Padre</label>
                                <select class="form-control" name="cuentaPadrea" id="cuentaPadrea">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        onclick="cerrarModal('ajaxModalAsientos')">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarAsiento()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
@endsection
