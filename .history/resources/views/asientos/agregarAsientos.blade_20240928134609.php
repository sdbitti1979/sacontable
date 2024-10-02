@extends('app')
@section('style')
    <style>
        .flatpickr-calendar {
            z-index: 2050 !important;
            /* Asegúrate de que sea mayor que el z-index del modal */
        }

        .swal-popup {
            z-index: 20600 !important;
            /* Ajusta el valor según tus necesidades */
        }
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
    </script>
@endsection
@section('modalBody')
    <div class="modal fade" id="ajaxModalAsientos" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ajaxModalLabel">Alta de Asiento</h5>
                    <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="datetimepicker">Seleccione Fecha:</label>
                            <input type="text" id="fechaasiento" class="form-control" placeholder="__/__/____">

                        </div>

                        <div class="mb-3">
                            <label for="descripcionAsiento" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcionAsiento" name="descripcionAsiento"></textarea>
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
