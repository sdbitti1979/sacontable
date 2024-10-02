@extends('app')

@section('style')
    <style>
        .container {
            max-width: 80% !important;
        }
    </style>
@endsection
@section('script')
<script type="text/javascript">
    var permisosTabla;
    $(document).ready(function($) {

        permisosTabla = $('#permisosTabla').DataTable({
            processing: true,
            serverSide: true,
            orderable: false,
            ajax: {
                url: "{{ route('permisos.data') }}", // Ruta del controlador
                type: "POST", // Cambiar el método a POST
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Agregar el token CSRF
                }
            },
            columns: [{
                    data: 'idpermiso',
                    name: 'idpermiso'
                },
                {
                    data: 'permiso',
                    name: 'descripcion'
                }
            ],
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });
    });
</script>
@endsection
@section('content')
    <main style="margin-top: 58px">
        <div class="container pt-4">
            <!-- Section: Main chart -->
            <section class="mb-4">
                <div class="card">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <button class="btn btn-secondary" onclick="history.back()">
                            <i class="fas fa-arrow-left"></i> Volver
                        </button>
                        <h5 class="mb-0 text-center"><strong>Permisos</strong>
                            <!-- <i class="fas fa-user-plus position-absolute end-0 me-3"
                                    style="font-size: 1.3rem; cursor: pointer;" onclick="agregarRol()"></i>-->
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <div id="modalContainer"></div>
                        <div class="table-responsive">
                            <table id="permisosTabla" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Permiso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
        </div>
        </section>
    </main>
@endsection
