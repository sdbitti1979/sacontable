@extends('app')

@section('content')
    <script type="text/javascript">
        $(document).ready(function($) {
            $("#usuario").focus();
        });
    </script>
    <div class="login-container">
        <h1>Sistema Contable</h1>
        <form action="{{ route('registerUser') }}" name="registerUser" method="POST">
            @csrf
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Nombre de Usuario..."
                    required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre..." required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellido..."
                    required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email..." required>
            </div>
            <div class="mb-3">
                <label for="cuil" class="form-label">CUIL</label>
                <input type="text" class="form-control" name="cuil" id="cuil" placeholder="CUIL..." required>
            </div>
            <div class="mb-3">
                <label for="idrol" class="form-label">Rol</label>
                <select name="idrol" id="idrol" class="form-select" required>
                    <option value="" selected disabled>Seleccione un rol...</option>
                    <option value="1">Administrador</option>
                    <option value="2">Usuario</option>
                    <!-- Agrega más opciones según los roles disponibles -->
                </select>
            </div>
            <div class="mb-3">
                <label for="clave" class="form-label">Contraseña</label>
                <input type="password" name="clave" class="form-control" id="clave" placeholder="Contraseña..."
                    required>
            </div>
            <div class="mb-3">
                <label for="clave_confirmation" class="form-label">Confirmar Contraseña</label>
                <input type="password" name="clave_confirmation" class="form-control" id="clave_confirmation"
                    placeholder="Confirmar Contraseña..." required>
            </div>

            <button type="submit" class="btn btn-primary">Registrar</button>
            <div class="mt-3">
                <a href="{{ route('login') }}">Volver al Logín</a>
            </div>
        </form>
    </div>
@endsection
