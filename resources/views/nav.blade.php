<header>

    <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white" data-mdb-collapse-initialized="true">
        <div class="position-sticky">
            <div class="list-group list-group-flush mx-3 mt-4">
                <a href="{{ route('dashboard') }}"
                    class="list-group-item list-group-item-action py-2 inicio nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                    data-mdb-ripple-init="" aria-current="true">
                    <i class="fas fa-home fa-fw me-3"></i><span>Inicio</span>
                </a>
           
                <a href="{{ route('usuarios') }}"
                    class="list-group-item list-group-item-action py-2 usuarios nav-link {{ request()->is('usuarios') ? 'active' : '' }}"
                    data-mdb-ripple-init="">
                    <!--<i class="fas fa-chart-area fa-fw me-3"></i>-->
                    <i class="fas fa-users fa-fw me-3"></i>
                    <span>Gestión de Usuarios</span>
                </a>                            
                <a href="{{ route('cuentas') }}" class="list-group-item list-group-item-action py-2  {{ request()->is('cuentas')  ? 'active' : '' }}" data-mdb-ripple-init="">
                    <i class="fas fa-file-invoice fa-fw me-3"></i>
                    <span>Plan de Cuentas</span>
                </a>
                <a href="{{ route('asientos') }}" class="list-group-item list-group-item-action py-2  {{ request()->is('asientos')  ? 'active' : '' }}" data-mdb-ripple-init="">
                    <i class="fas fa-calculator fa-fw me-3"></i>
                    <span>Asientos Contables</span>
                </a>
                <a href="{{ route('reportes') }}" class="list-group-item list-group-item-action py-2  {{ request()->is('reportes')  ? 'active' : '' }}" data-mdb-ripple-init="">
                    <i class="fas fa-scroll fa-fw me-3"></i>
                    <span>Reportes</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action py-2  {{ request()->is('inventario')  ? 'active' : '' }}" data-mdb-ripple-init="">
                    <i class="fas fa-boxes fa-fw me-3"></i>
                    <span>Inventarios</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action py-2  {{ request()->is('compras')  ? 'active' : '' }}" data-mdb-ripple-init="">
                    <i class="fas fa-shopping-cart fa-fw me-3"></i>
                    <span>Compras</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action py-2  {{ request()->is('ventas')  ? 'active' : '' }}" data-mdb-ripple-init="">
                    <i class="fas fa-blender-phone fa-fw me-3"></i>
                    <span>Ventas</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action py-2  {{ request()->is('banco')  ? 'active' : '' }}" data-mdb-ripple-init="">
                    <i class="fas fa-university fa-fw me-3"></i>
                    <span>Bancos</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action py-2  {{ request()->is('nomina')  ? 'active' : '' }}" data-mdb-ripple-init="">
                    <i class="fas fa-address-book fa-fw me-3"></i>
                    <span>Nóminas</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action py-2  {{ request()->is('impuestos')  ? 'active' : '' }}" data-mdb-ripple-init="">
                    <i class="fas fa-file-invoice-dollar fa-fw me-3"></i>
                    <span>Impuestos</span>
                </a>
                
                <a href="{{ route('configuracion') }}" class="list-group-item list-group-item-action py-2  {{ request()->is('configuracion') || request()->is('roles') || request()->is('permisos') ? 'active' : '' }}" data-mdb-ripple-init="">
                    <i class="fas fa-cogs fa-fw me-3"></i>
                    <span>Configuración</span>
                </a>                     
                <a href="./logout" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init="">
                    <i class="fas fa-door-open fa-fw me-3"></i>
                    <span>Salir</span>
                </a>

            </div>
        </div>
    </nav>
    <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <!-- Container wrapper -->
        <div class="container-fluid">
            <!-- Toggle button -->
            <button class="navbar-toggler" type="button" data-mdb-collapse-init data-mdb-target="#sidebarMenu"
                aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Brand -->
            <a class="navbar-brand" href="#">
                <img src="{{ asset('img/nav-bar.png') }}" height="25" alt="" loading="lazy" />
            </a>
            <!-- Search form -->
            <!--<form class="d-none d-md-flex input-group w-auto my-auto">
                <input autocomplete="off" type="search" class="form-control rounded"
                    placeholder='Search (ctrl + "/" to focus)' style="min-width: 225px" />
                <span class="input-group-text border-0"><i class="fas fa-search"></i></span>
            </form>-->

            <!-- Right links -->
            <ul class="navbar-nav ms-auto d-flex flex-row">
                <!-- Notification dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link me-3 me-lg-0 dropdown-toggle hidden-arrow" href="#"
                        id="navbarDropdownMenuLink" role="button" data-mdb-dropdown-init aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="badge rounded-pill badge-notification bg-danger">1</span>
                    </a>
                    <!--<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="#">Some news</a></li>
                        <li><a class="dropdown-item" href="#">Another news</a></li>
                        <li>
                            <a class="dropdown-item" href="#">Something else</a>
                        </li>
                    </ul>-->
                </li>

                <!-- Icon -->
                <!--<li class="nav-item">
                    <a class="nav-link me-3 me-lg-0" href="#">
                        <i class="fas fa-fill-drip"></i>
                    </a>
                </li>-->
                <!-- Icon -->
                <li class="nav-item me-3 me-lg-0">
                    <a class="nav-link" href="https://sdbitti1979.github.io/sacontable/" target="_blank">
                        <i class="fab fa-github"></i>
                    </a>
                </li>

                <!-- Icon dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link me-3 me-lg-0 dropdown-toggle hidden-arrow" href="#" id="navbarDropdown"
                        role="button" data-mdb-dropdown-init aria-expanded="false">
                        <i class="united kingdom flag m-0"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="#"><i class="united kingdom flag"></i>English
                                <i class="fa fa-check text-success ms-2"></i></a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"><i class="poland flag"></i>Polski</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"><i class="china flag"></i>中文</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"><i class="japan flag"></i>日本語</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"><i class="germany flag"></i>Deutsch</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"><i class="france flag"></i>Français</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"><i class="spain flag"></i>Español</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"><i class="russia flag"></i>Русский</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"><i class="portugal flag"></i>Português</a>
                        </li>
                    </ul>
                </li>

                <!-- Avatar -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle hidden-arrow d-flex align-items-center" href="#"
                        id="navbarDropdownMenuLink" role="button" data-mdb-dropdown-init aria-expanded="false">
                        <i class="fas fa-user-circle fa-lg"></i>
                        <!--<img src="https://mdbootstrap.com/img/Photos/Avatars/img (31).jpg" class="rounded-circle"
                            height="22" alt="" loading="lazy" />-->
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="#">Mi cuenta</a></li>
                        <li><a class="dropdown-item" href="#">Configuración</a></li>
                        <li><a class="dropdown-item" href="./logout">Salir</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->
</header>
