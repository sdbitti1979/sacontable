@extends('app')

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#email").focus();
        });
    </script>
@endsection
@section('content')
    <div class="login-container">
        <h1>Sistema Contable</h1>
        <form action="{{ route('validatelogin') }}" name="validatelogin" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email..."
                    aria-describedby="emailHelp">
                <!--<div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>-->
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Contraseña...">
            </div>
            <!--<div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div>-->
            <button type="submit" class="btn btn-primary">Ingresar</button>
            <!-- Enlace al formulario de registro -->
            <div class="mt-3">
                <a href="{{ route('showRegisterForm') }}">¿No tienes una cuenta? Regístrate aquí</a>
            </div>
        </form>
    </div>
@endsection
