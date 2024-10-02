<script type="text/javascript">
    $(document).ready(function() {

    });
</script>

<div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajaxModalLabel">Alta de Usuario</h5>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input-group mb-2 col-lg-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="item_desc">CUIL</span>
                                </div>
                                <input type="text" class="form-control" id="cuil" aria-describedby="cuil"
                                    maxlength="11">
                            </div>
                            <div class="input-group mb-2 col-lg-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="item_desc">Usuario</span>
                                </div>
                                <input type="text" class="form-control" id="usuario" aria-describedby="usuario"
                                    maxlength="15">
                            </div>
                            <div class="input-group mb-2 col-lg-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="item_desc">Apellido</span>
                                </div>
                                <input type="text" class="form-control" id="apellido" aria-describedby="apellido"
                                    maxlength="30">
                            </div>
                            <div class="input-group mb-2 col-lg-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="item_desc">Nombre</span>
                                </div>
                                <input type="text" class="form-control" id="nombre" aria-describedby="nombre"
                                    maxlength="30">
                            </div>
                            <div class="input-group mb-2 col-lg-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="item_desc">Rol</span>
                                </div>
                                <select name="rol" id="rol" class="form-control">
                                    <option value="0">Seleccione...</option>
                                    @foreach ($roles as $value => $rol)
                                        <option value="{{ $rol['idrol'] }}">
                                            {{ $rol['rol'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-2 col-lg-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="item_desc">Email</span>
                                </div>
                                <input type="text" class="form-control" id="email" aria-describedby="email"
                                    maxlength="100">
                            </div>
                            <div class="input-group mb-2 col-lg-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="item_desc">Clave</span>
                                </div>
                                <input type="password" class="form-control" id="clave" aria-describedby="clave"
                                    maxlength="500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"  onclick="cerrarModal()">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarUsuario()">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
