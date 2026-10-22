<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Witaj w systemie nauki jazdy</h5>
                    <p class="card-text">
                        Jesteś zalogowany jako: <strong>{{ Auth::user()->name }} {{ Auth::user()->surname }}</strong>.
                        Twoja rola: <span class="badge bg-primary">{{ ucfirst(Auth::user()->role) }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
