@extends('app')

@section('content')
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('cantidadRoles') }}",
                type: 'POST',
                data: {
                    _method: 'post', // Aunque este método se usa solo con el método DELETE en REST, aquí está para claridad
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    $("#h3roles").html(response.cantidad);
                },
                error: function(xhr) {
                    alert('Error al realizar la solicitud.');
                }
            });

            $.ajax({
                url: "{{ route('cantidadPermisos') }}",
                type: 'POST',
                data: {
                    _method: 'post', // Aunque este método se usa solo con el método DELETE en REST, aquí está para claridad
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    $("#h3permisos").html(response.cantidad);
                },
                error: function(xhr) {
                    alert('Error al realizar la solicitud.');
                }
            });
        });
    </script>

    <main style="margin-top: 58px">
        <div class="container pt-4">
            <!--Section: Minimal statistics cards-->
            <section>
                <div class="row">
                    <div class="col-xl-3 col-sm-6 col-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between px-md-1">
                                    <div class="align-self-center">
                                        <a href="{{ route('roles') }}">
                                            <i class="fas fa-user-tag text-info fa-3x"></i>
                                        </a>
                                    </div>
                                    <div class="text-end">
                                        <h3 id="h3roles">0</h3>
                                        <p class="mb-0">Roles</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between px-md-1">
                                    <div class="align-self-center">
                                        <a href="{{ route('permisos') }}">
                                            <i class="fas fa-sliders-h  text-warning fa-3x"></i>
                                        </a>
                                    </div>
                                    <div class="text-end">
                                        <h3 id="h3permisos">0</h3>
                                        <p class="mb-0">Permisos</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
