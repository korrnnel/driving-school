<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse border-end shadow-sm">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item mb-1">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active text-primary fw-bold' : 'text-dark' }}"
                    href="{{ route('dashboard') }}">
                    Dashboard
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active text-primary fw-bold' : 'text-dark' }}"
                    href="{{ route('admin.courses.index') }}">
                    Kursy
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link {{ request()->routeIs('admin.vehicles.*') ? 'active text-primary fw-bold' : 'text-dark' }}"
                    href="{{ route('admin.vehicles.index') }}">
                    Pojazdy
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active text-primary fw-bold' : 'text-dark' }}"
                    href="{{ route('admin.users.index') }}">
                    Użytkownicy
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link {{ request()->routeIs('admin.enrollments.*') ? 'active text-primary fw-bold' : 'text-dark' }}"
                    href="{{ route('admin.enrollments.index') }}">
                    Zapisy na kursy
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link {{ request()->routeIs('admin.lessons.*') ? 'active text-primary fw-bold' : 'text-dark' }}"
                    href="{{ route('admin.lessons.index') }}">
                    Jazdy (Lekcje)
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link {{ request()->routeIs('admin.instructor-availabilities.*') ? 'active text-primary fw-bold' : 'text-dark' }}"
                    href="{{ route('admin.instructor-availabilities.index') }}">
                    Grafik Instruktorów
                </a>
            </li>
        </ul>
    </div>
</nav>
