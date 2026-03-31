<nav class="navbar bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            @auth
                <a class="bot botNegro" href="{{ url('/ocompras') }}" title="Inicio" style="font-size: 20px;">🪪</a>
            @endauth
        </div>
        <div class="mx-auto">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.png') }}" style="width:40px; height:auto;" alt="Logo">
            </a>
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            @guest
                <a href="{{ route('login') }}" class="bot botNegro" title="Iniciar sesión">🟠👤</a>
            @else
                <span class="small fw-semibold text-truncate d-inline-block" style="font-size: 1.1rem; max-width:120px;">
                    {{ explode(' ', Auth::user()->name)[0] }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="bot botNegro" title="Cerrar sesión">
                        @canany(['admin', 'adminMax']) 🟢⚙️ @else 🟢👤 @endcanany
                    </button>
                </form>
            @endguest
        </div>
        <div class="offcanvas offcanvas-start cardSec" tabindex="-1" id="offcanvasNavbar">
            <div class="cardSec-header">
                <span class="fs-5">Menú</span>
                <button type="button" class="bot botNegro" data-bs-dismiss="offcanvas">X</button>
            </div>
            <div class="cardSec-body">
                <ul class="navbar-nav pe-3">
                    <li class="nav-item custom-dropdown-item">
                        <a href="#" class="nav-link menu-trigger">💸 Admin</a>
                        <ul class="submenu d-none list-unstyled ps-2 border-start">
                            <li><a href="{{ url('/ocompras') }}" class="nav-link small">🛒 Orden de Compra</a></li>
                            <li><a href="{{ url('/recepcionoc') }}" class="nav-link small">📥 Recepción OC</a></li>
                            <li class="border-top"><a href="{{ url('/kardex') }}" class="nav-link small">🗂️ Kardex</a></li>
                        </ul>                        
                    </li>
                </ul>
                <ul class="navbar-nav pe-3">
                    <a href="#" class="nav-link menu-trigger">🧱 Materiales</a>
                    <ul class="submenu d-none list-unstyled ps-3 border-start">
                        <li><a href="{{ url('/fichamats') }}" class="nav-link small">🗂️ Ficha del Material</a></li>
                        <li><a href="{{ url('/clases') }}" class="nav-link small">🛠️ TablaHerrajes</a></li>
                        <li><a href="{{ url('/clases') }}" class="nav-link small">🧩 Clases</a></li>
                        <li><a href="{{ url('/marcas') }}" class="nav-link small">🧿 Marcas</a></li>
                        <li><a href="{{ url('/lineas') }}" class="nav-link small">📁 Líneas</a></li>
                    </ul>
                </ul>  
                <ul class="navbar-nav pe-3">
                    <a href="#" class="nav-link menu-trigger">🧱 Consultas y Reportes</a>
                    <ul class="submenu d-none list-unstyled ps-3 border-start">
                        <li><a href="{{ url('/consultas') }}" class="nav-link small">🔎 Compras</a></li>
                    </ul>
                </ul>                  
                <ul class="navbar-nav pe-3">
                    <li class="nav-item custom-dropdown-item">
                        <a href="#" class="nav-link menu-trigger">🔗 Catálogos</a>
                        <ul class="submenu d-none list-unstyled ps-3">
                            <li class="nav-item">
                                <a href="#" class="nav-link menu-trigger">🏢 Empresas</a>
                                <ul class="submenu d-none list-unstyled ps-3 border-start">
                                    <li><a href="{{ url('/clientes') }}" class="nav-link small">👥 Clientes</a></li>
                                    <li><a href="{{ url('/proveedores') }}" class="nav-link small">🏭 Proveedores</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link menu-trigger">🧰 Configuración</a>
                                <ul class="submenu d-none list-unstyled ps-3 border-start">
                                    <li><a href="{{ url('/negocios') }}" class="nav-link small">🏠 Mi Empresa</a></li>
                                    <li><a href="{{ url('/divisions') }}" class="nav-link small">📌 Divisiones</a></li>
                                    <li><a href="{{ url('/divsbodegas') }}" class="nav-link small">📌 Bodegas</a></li>
                                    <li><a href="{{ url('/unidads') }}" class="nav-link small">🚚 Unidades</a></li>
                                    <li><a href="{{ url('/colors') }}" class="nav-link small">🌈 Colores</a></li>
                                    <li><a href="{{ url('/colorables') }}" class="nav-link small">🖌️ Colorables</a>
                                    <li><a href="{{ url('/vidrios') }}" class="nav-link small">🪟 Vidrios</a></li>
                                    <li><a href="{{ url('/users') }}" class="nav-link small">🧑‍💻 Usuarios</a></li>
                                    <li><a href="{{ url('/catalogos') }}" class="nav-link small">🧩 Básicos</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>                              
            </div>
        </div>
    </div>
</nav>
<style>
    .menu-trigger { cursor: pointer; position: relative; }
    .menu-trigger::after { content: ' ▾'; font-size: 0.8em; color: gray; }
    .menu-trigger.active::after { content: ' ▴'; }
    .submenu { background: rgba(0,0,0,0.02); border-radius: 4px; }
    .nav-link:hover { color: #0d6efd; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuTriggers = document.querySelectorAll('.menu-trigger');
    menuTriggers.forEach(function(trigger) {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const nextSubmenu = this.nextElementSibling;
            if (nextSubmenu) {
                const isHidden = nextSubmenu.classList.contains('d-none');
                this.classList.toggle('active');
                if (isHidden) {
                    nextSubmenu.classList.remove('d-none');
                } else {
                    nextSubmenu.classList.add('d-none');
                }
            }
        });
    });
});
</script>