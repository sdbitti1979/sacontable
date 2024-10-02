@extends('app')

@section('modalBody')
    <div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ajaxModalLabel">Alta de Asiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="datetimepicker" class="form-label">Seleccione Fecha y Hora</label>
                            <input type="text" id="datetimepicker" class="form-control" placeholder="YYYY-MM-DD HH:MM">
                        </div>

                        <div class="mb-3">
                            <label for="descripcionAsiento" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcionAsiento" name="descripcionAsiento"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarAsiento()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <style>
        .td-picker-container {
            display: block !important;
            z-index: 10500;
            /* Ajusta este valor según el contexto de tu aplicación */
        }
    </style>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            // Esto se ejecuta cuando el DOM está listo
            $(document).on('shown.bs.modal', '#modal-id', function() {
                // Inicializa el flatpickr cuando el modal esté completamente mostrado
                flatpickr("#datetime", {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                });
            });
        });
    </script>
@endsection
