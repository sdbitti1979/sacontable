@section('modalBody')
    <!-- Contenido específico para el cuerpo del modal -->
    <form>
        <div class="mb-3">
            <label for="fechaAsiento" class="form-label">Fecha</label>
            <input type="date" class="form-control" id="fechaAsiento" name="fechaAsiento">
        </div>
        <div class="mb-3">
            <label for="descripcionAsiento" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcionAsiento" name="descripcionAsiento"></textarea>
        </div>
        <button type="button" class="btn btn-primary" onclick="guardarAsiento()">Guardar</button>
    </form>
@endsection
