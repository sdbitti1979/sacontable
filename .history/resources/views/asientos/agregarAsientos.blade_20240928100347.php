@extends('app')
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            document.addEventListener('DOMContentLoaded', function() {
                const element = document.getElementById('datetime');
                new tempusDominus.TempusDominus(element, {
                    display: {
                        components: {
                            decades: true,
                            year: true,
                            month: true,
                            date: true,
                            hours: true,
                            minutes: true,
                            seconds: false,
                            useTwentyfourHour: true
                        }
                    }
                });
            });

        });
    </script>
@endsection
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
                            <label for="datetime" class="form-label">Seleccione Fecha y Hora</label>
                            <div class="input-group" id="datetimepicker">
                                <input type="text" class="form-control" id="datetime" placeholder="YYYY-MM-DD HH:MM:SS">
                                <span class="input-group-text">
                                    <i class="bi bi-calendar"></i>
                                </span>
                            </div>
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
