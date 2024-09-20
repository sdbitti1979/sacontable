<div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajaxModalLabel">Edición de Usuario</h5>                
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
                                    maxlength="11" value="{{$usuario["cuil"]}}">
                            </div>
                            <div class="input-group mb-2 col-lg-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="item_desc">Usuario</span>
                                </div>
                                <input type="text" class="form-control" id="usuario" aria-describedby="usuario"
                                    maxlength="15" value="{{$usuario["usuario"]}}">
                            </div>
                            <div class="input-group mb-2 col-lg-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="item_desc">Apellido</span>
                                </div>
                                <input type="text" class="form-control" id="apellido" aria-describedby="apellido"
                                    maxlength="30" value="{{$usuario["apellido"]}}">
                            </div>
                            <div class="input-group mb-2 col-lg-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="item_desc">Nombre</span>
                                </div>
                                <input type="text" class="form-control" id="nombre" aria-describedby="nombre"
                                    maxlength="30" value="{{$usuario["nombre"]}}">
                            </div>
                            <div class="input-group mb-2 col-lg-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="item_desc">Rol</span>
                                </div>
                                <select name="rol" id="rol" class="form-control">
                                    <option value="0">Seleccione...</option>
                                    @foreach ($roles as $value => $rol)
                                        <option value="{{ $rol['idrol'] }}" {{ ($rol['idrol'] == $usuario['idrol']) ? 'selected' : '' }}>                                            
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
                                    maxlength="100" value="{{$usuario["email"]}}">
                            </div>                          
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="cerrarModal()">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="actualizarUsuario({{$usuario['idusuario']}})">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
