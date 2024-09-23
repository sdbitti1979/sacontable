<header>
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="collapse d-lg-block sidebar bg-light">
        <div class="position-sticky">
            <div class="list-group list-group-flush mx-3 mt-4">
                <a href="{{ route('dashboard') }}"
                    class="list-group-item list-group-item-action py-2 nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home fa-fw me-3"></i><span>Inicio</span>
                </a>

                <a href="{{ route('usuarios') }}"
                    class="list-group-item list-group-item-action py-2 nav-link {{ request()->is('usuarios') ? 'active' : '' }}">
                    <i class="fas fa-users fa-fw me-3"></i><span>Gestión de Usuarios</span>
                </a>

                <a href="{{ route('cuentas') }}"
                    class="list-group-item list-group-item-action py-2 nav-link {{ request()->is('cuentas') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice fa-fw me-3"></i><span>Plan de Cuentas</span>
                </a>

                <a href="{{ route('asientos') }}"
                    class="list-group-item list-group-item-action py-2 nav-link {{ request()->is('asientos') ? 'active' : '' }}">
                    <i class="fas fa-calculator fa-fw me-3"></i><span>Asientos Contables</span>
                </a>

                <a href="{{ route('reportes') }}"
                    class="list-group-item list-group-item-action py-2 nav-link {{ request()->is('reportes') ? 'active' : '' }}">
                    <i class="fas fa-scroll fa-fw me-3"></i><span>Reportes</span>
                </a>

                <a href="#" class="list-group-item list-group-item-action py-2 nav-link {{ request()->is('inventario') ? 'active' : '' }}">
                    <i class="fas fa-boxes fa-fw me-3"></i><span>Inventarios</span>
                </a>

                <a href="#" class="list-group-item list-group-item-action py-2 nav-link {{ request()->is('compras') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart fa-fw me-3"></i><span>Compras</span>
                </a>

                <a href="#" class="list-group-item list-group-item-action py-2 nav-link {{ request()->is('ventas') ? 'active' : '' }}">
                    <i class="fas fa-blender-phone fa-fw me-3"></i><span>Ventas</span>
                </a>

                <a href="#" class="list-group-item list-group-item-action py-2 nav-link {{ request()->is('banco') ? 'active' : '' }}">
                    <i class="fas fa-university fa-fw me-3"></i><span>Bancos</span>
                </a>

                <a href="#" class="list-group-item list-group-item-action py-2 nav-link {{ request()->is('nomina') ? 'active' : '' }}">
                    <i class="fas fa-address-book fa-fw me-3"></i><span>Nóminas</span>
                </a>

                <a href="#" class="list-group-item list-group-item-action py-2 nav-link {{ request()->is('impuestos') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar fa-fw me-3"></i><span>Impuestos</span>
                </a>

                <a href="{{ route('configuracion') }}"
                    class="list-group-item list-group-item-action py-2 nav-link {{ request()->is('configuracion') ? 'active' : '' }}">
                    <i class="fas fa-cogs fa-fw me-3"></i><span>Configuración</span>
                </a>

                <a href="./logout" class="list-group-item list-group-item-action py-2 nav-link">
                    <i class="fas fa-door-open fa-fw me-3"></i><span>Salir</span>
                </a>
            </div>
        </div>
    </nav>
    <!-- Sidebar End -->

    <!-- Navbar -->
    <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container-fluid">
            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-mdb-collapse-init data-mdb-target="#sidebarMenu"
                aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Brand -->
            <a class="navbar-brand" href="#">
                <img src="{{ asset('img/logosa.png') }}" height="40" alt="Logo" loading="lazy" />
            </a>

            <!-- Right Links -->
            <ul class="navbar-nav ms-auto d-flex flex-row align-items-center">
                <!-- Notification Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link me-3 me-lg-0 dropdown-toggle hidden-arrow" href="#" id="navbarDropdownMenuLink"
                        role="button" data-mdb-dropdown-init aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="badge rounded-pill badge-notification bg-danger">1</span>
                    </a>
                </li>

                <!-- GitHub Link -->
                <li class="nav-item me-3 me-lg-0">
                    <a class="nav-link" href="https://sdbitti1979.github.io/sacontable/" target="_blank">
                        <i class="fab fa-github"></i>
                    </a>
                </li>

                <!-- Language Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link me-3 me-lg-0 dropdown-toggle hidden-arrow" href="#" id="navbarDropdown"
                        role="button" data-mdb-dropdown-init aria-expanded="false">
                        <i class="united kingdom flag m-0"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#"><i class="united kingdom flag"></i>English</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#"><i class="poland flag"></i>Polski</a></li>
                        <li><a class="dropdown-item" href="#"><i class="china flag"></i>中文</a></li>
                        <li><a class="dropdown-item" href="#"><i class="japan flag"></i>日本語</a></li>
                        <li><a class="dropdown-item" href="#"><i class="germany flag"></i>Deutsch</a></li>
                        <li><a class="dropdown-item" href="#"><i class="france flag"></i>Français</a></li>
                        <li><a class="dropdown-item" href="#"><i class="spain flag"></i>Español</a></li>
                        <li><a class="dropdown-item" href="#"><i class="russia flag"></i>Русский</a></li>
                        <li><a class="dropdown-item" href="#"><i class="portugal flag"></i>Português</a></li>
                    </ul>
                </li>

                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle hidden-arrow d-flex align-items-center" href="#"
                        id="navbarDropdownMenuLink" role="button" data-mdb-dropdown-init aria-expanded="false">
                        <i class="fas fa-user-circle fa-lg"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="#">Mi cuenta</a></li>
                        <li><a class="dropdown-item" href="#">Configuración</a></li>
                        <li><a class="dropdown-item" href="./logout">Salir</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Navbar End -->
</header>
