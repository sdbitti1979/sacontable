@extends('app')

@section('content')
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
                                        <!--<i class="fas fa-pencil-alt text-info fa-3x"></i>-->
                                        <i class="fas fa-user-tag text-info fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                        <h3>2</h3>
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
                                        <i class="fas fa-sliders-h  text-warning fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                        <h3>0</h3>
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
