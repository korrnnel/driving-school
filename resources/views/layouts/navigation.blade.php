<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="{{ !empty($withSidebar) ? 'container-fluid' : 'container' }}">
        <div class="d-flex align-items-center">
            @if(!empty($withSidebar) && Auth::check())
                <button class="btn btn-outline-light btn-sm d-md-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Menu panelu">
                    Panel
                </button>
            @endif

            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                {{ config('app.name', 'OSK L-auto') }}
            </a>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active fw-semibold' : '' }}" href="{{ route('home') }}">Strona główna</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.courses') ? 'active fw-semibold' : '' }}" href="{{ route('public.courses') }}">Kursy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.fleet') ? 'active fw-semibold' : '' }}" href="{{ route('public.fleet') }}">Flota</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.about') ? 'active fw-semibold' : '' }}" href="{{ route('public.about') }}">O nas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('public.contact') ? 'active fw-semibold' : '' }}" href="{{ route('public.contact') }}">Kontakt</a>
                </li>
            </ul>

            <div class="d-flex align-items-center flex-wrap gap-2 mt-2 mt-lg-0">
                @auth
                    <span class="text-white-50 small me-1">
                        {{ Auth::user()->name }} ({{ Auth::user()->role_translated }})
                    </span>

                    @if(empty($withSidebar))
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">Mój panel</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">Wyloguj się</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Zaloguj się</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Zarejestruj się</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
