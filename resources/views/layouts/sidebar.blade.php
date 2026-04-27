<nav class="navbar bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            @auth
                <a class="bot botNegro" href="{{ url('/temp') }}" title="Inicio" style="font-size: 20px;">🪪</a>
            @endauth
        </div>
        <div class="mx-auto">
            <a href="{{ url('/home') }}">
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
            @auth
                <div class="cardSec-body" style="max-height: calc(100vh - 60px); overflow-y: auto;">
                    <ul class="navbar-nav pe-3">
                        <li class="nav-item custom-dropdown-item">
                            <a href="#" class="nav-link menu-trigger">🧩 Admin</a>
                            <ul class="submenu d-none list-unstyled ps-2 border-start">
                                <li><a href="{{ url('/facimports') }}" class="nav-link small">🌍 Importación</a></li>
                                <li><a href="{{ url('/recibirimports') }}" class="nav-link small">📥 Recepción de Import</a></li>
                                <li><a href="{{ url('/adminfolios') }}" class="nav-link small">🏷️ Folios</a></li>
                                <li><a href="{{ url('#') }}" class="nav-link small">🔌 API Go Aduanas</a></li>
                            </ul>
                        </li>
                    </ul>                
                    <ul class="navbar-nav pe-3">
                        <li class="nav-item custom-dropdown-item">
                            <a href="#" class="nav-link menu-trigger">🔗 Catálogos</a>
                                <ul class="submenu d-none list-unstyled ps-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link menu-trigger">🏢 Generales</a>
                                        <ul class="submenu d-none list-unstyled ps-3 border-start">
                                            <li><a href="{{ url('/clientes') }}" class="nav-link small">👥 Clientes</a></li>
                                            <li><a href="{{ url('/deptos') }}" class="nav-link small">🏬 Departamentos</a></li>
                                            <li><a href="{{ url('/unidads') }}" class="nav-link small">📐 Unidades</a></li>
                                            <li><a href="{{ url('/sizes') }}" class="nav-link small">📏 Sizes</a></li>
                                            <li><a href="{{ url('/formas') }}" class="nav-link small">🔷 Formas</a></li>
                                            <li><a href="{{ url('/origens') }}" class="nav-link small">🌐 Orígenes</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link menu-trigger">🧱 Materiales</a>
                                        <ul class="submenu d-none list-unstyled ps-3 border-start">
                                            <li><a href="{{ url('/permisos') }}" class="nav-link small">🔐 Permisos</a></li>
                                            <li><a href="{{ url('/arancels') }}" class="nav-link small">💰 Aranceles</a></li>
                                            <li><a href="{{ url('/tipos') }}" class="nav-link small">🧩 Tipos</a></li>
                                            <li><a href="{{ url('/clases') }}" class="nav-link small">🗂️ Clases</a></li>
                                            <li><a href="{{ url('/materials') }}" class="nav-link small">🧱 Materiales</a></li>
                                            <li><a href="{{ url('/estilos') }}" class="nav-link small">🎨 Estilos</a></li>
                                        </ul>
                                    </li>                            

                                    <li class="nav-item">
                                        <a href="#" class="nav-link menu-trigger">⚙️ Configuración</a>
                                        <ul class="submenu d-none list-unstyled ps-3 border-start">
                                            <li><a href="{{ url('/users') }}" class="nav-link small">🧑‍💻 Usuarios</a></li>
                                            <li><a href="{{ url('/catalogos') }}" class="nav-link small">🧩 Config</a></li>
                                        </ul>
                                    </li>
                                </ul>
                        </li>
                    </ul>                              
                </div>
            @endauth
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