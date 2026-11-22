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

            <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
                @auth
                    @if(empty($withSidebar))
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">Przejdź do panelu</a>
                    @endif

                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white px-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }} ({{ Auth::user()->role_translated }})
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Wyloguj się</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Zaloguj się</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Zarejestruj się</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
