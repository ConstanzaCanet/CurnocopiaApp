<nav class="main-header navbar pb-3
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
        
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">

        <!-- Carrito de Compras -->
        <li class="nav-item dropdown">
            <a class="nav-link" href="{{ route('cart.index') }}">
                <!-- SVG del Carrito -->
                <i class="fas fa-shopping-cart"></i>
                <!-- Cantidad de productos en el carrito -->
                <span class="badge badge-pill badge-danger navbar-badge cart-badge">
                    {{ Cart::count() }}
                </span>
            </a>
        </li>
        
        {{-- Custom right links --}}
       {{--@yield('content_top_nav_right')--}}

        {{-- Configured right links----Busqueda por items--}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
      
    </ul>

</nav>


<style>

.cart-badge {
        font-size: 0.70rem;
        padding: 0.25em 0.5em;
        position: absolute;
        top: 10px;
        right: 10px;
        transform: translate(50%, -50%);
        border-radius: 50%;
        min-width: 20px;
        height: 20px;
        line-height: 18px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .nav-link {
        position: relative;
    }

</style>