@extends('app')

@section('script')
<script type="text/javascript">
     $(document).ready(function($) {

     });

     function agregarCuenta() {
            $.ajax({
                url: "{{ route('cuentas.agregarCuenta') }}?modal=true",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF para protección
                },
                success: function(response) {
                    //console.log(response);
                    // Cargar el contenido del modal en el contenedor
                    $('#modalContainer').html(response);


                    // Inicializar el modal y mostrarlo
                    myModal = new bootstrap.Modal(document.getElementById('ajaxModalCuentas'));
                    //console.log(myModal);
                    myModal.show();
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
                    <h5 class="mb-0 text-center"><strong>Plan de Cuentas</strong>
                        @if (in_array('CUENTAS.CREAR', $permissions ?? []))
                            <i class="fas fa-plus position-absolute end-0 me-3"
                                style="font-size: 1.3rem; cursor: pointer;" onclick="agregarCuenta()"></i>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <div id="modalContainer"></div>
                    <div class="table-responsive">
                        <table id="asientos-table" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th>Clasificación</th>
                                    <th>Saldo Actual</th>
                                    <th>Padre</th>
                                    <th>Utilizada</th>
                                    <th>Eliminada</th>
                                    <th>Usuario</th>
                                    <th>Recibe Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection
