<!-- resources/views/home.blade.php -->
@extends('app')

@section('title', 'Home Page')
@section('script')
    <script type="text/javascript">
        function agregarAsiento() {
            $.ajax({
                url: "{{ route('asientos.agregarAsiento') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF para protección
                },
                success: function(response) {
                    console.log(response);
                    // Cargar el contenido del modal en el contenedor
                    $('#modalContainerAsientos').html(response);

                    // Inicializar el modal y mostrarlo
                    //var myModal = new bootstrap.Modal(document.getElementById('ajaxModal'));
                    //myModal.show();
                },
                error: function(xhr) {
                    console.error('Error al cargar el modal');
                }
            });
        }
    </script>
@endsection

@section('content')
    <main style="margin-top: 58px">
        <div class="container pt-4">
            <!-- Section: Main content -->
            <section class="mb-4">
                <div class="card">
                    <div class="card-header py-3">
                        <h5 class="mb-0 text-center"><strong>Asientos Contables</strong>
                            @if (in_array('USUARIOS.CREAR', $permissions ?? []))
                                <i class="fas fa-plus position-absolute end-0 me-3"
                                    style="font-size: 1.3rem; cursor: pointer;" onclick="agregarAsiento()"></i>
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Descripción</th>
                                        <th>Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2023-09-22</td>
                                        <td>Descripción de prueba</td>
                                        <td>Usuario de prueba</td>
                                    </tr>
                                    <!-- Aquí se pueden agregar más filas dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection


