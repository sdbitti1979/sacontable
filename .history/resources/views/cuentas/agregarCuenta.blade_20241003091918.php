@extends('app')
@section('style')
    <style>
        .modal-content{
            width: 1000px;
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ajaxModalLabel">Alta de Asiento</h5>
                    <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3 col-span-6">
                            <label for="datetimepicker">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" >
                        </div>
                        <div class="mb-3">
                            <label for="descripcionAsiento" class="form-label">Código</label>
                            <input type="text" id="codigo" name="codigo" class="form-control" >
                        </div>
                        <div class="mb-3">
                            <label for="descripcionAsiento" class="form-label">Clasificación</label>
                            <select class="form-control"></select>
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
