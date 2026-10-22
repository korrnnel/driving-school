<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse border-end shadow-sm">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item mb-1">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active text-primary fw-bold' : 'text-dark' }}" href="{{ route('dashboard') }}">
                    Dashboard
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active text-primary fw-bold' : 'text-dark' }}" href="{{ route('admin.courses.index') }}">
                    Kursy
                </a>
            </li>
            <li class="nav-item mb-1">
    <a class="nav-link {{ request()->routeIs('admin.vehicles.*') ? 'active text-primary fw-bold' : 'text-dark' }}" href="{{ route('admin.vehicles.index') }}">
        Pojazdy
    </a>
</li>
        </ul>
    </div>
</nav>
