@extends('layouts.public')

@section('title', 'Nasza flota - ' . config('app.name', 'OSK L-auto'))

@section('content')
    <section class="bg-white border-bottom py-4 mb-4">
        <div class="container">
            <h1 class="h2 fw-bold mb-2">Nasza flota pojazdów</h1>
            <p class="text-muted mb-3">Szkolimy na nowoczesnych, regularnie serwisowanych i w pełni sprawnych pojazdach egzaminacyjnych.</p>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('public.fleet') }}" class="btn btn-sm {{ $category === null ? 'btn-primary' : 'btn-outline-primary' }}">Wszystkie</a>
                <a href="{{ route('public.fleet', ['category' => 'A']) }}" class="btn btn-sm {{ $category === 'A' ? 'btn-primary' : 'btn-outline-primary' }}">Motocykle (Kat. A)</a>
                <a href="{{ route('public.fleet', ['category' => 'B']) }}" class="btn btn-sm {{ $category === 'B' ? 'btn-primary' : 'btn-outline-primary' }}">Samochody (Kat. B)</a>
                <a href="{{ route('public.fleet', ['category' => 'C']) }}" class="btn btn-sm {{ $category === 'C' ? 'btn-primary' : 'btn-outline-primary' }}">Ciężarówki (Kat. C)</a>
            </div>
        </div>
    </section>

    <section class="pb-5">
        <div class="container">
            <div class="row g-4">
                @forelse ($vehicles as $vehicle)
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-primary">Kat. {{ $vehicle->category }}</span>
                                    <span class="badge bg-success">Aktywny</span>
                                </div>

                                <h5 class="card-title fw-bold mb-2">{{ $vehicle->brand }} {{ $vehicle->model }}</h5>

                                <div class="mt-3 p-2 bg-light border rounded d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Tablica rejestracyjna</span>
                                    <span class="font-monospace fw-bold text-dark">{{ $vehicle->registration_number }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center py-5 text-muted">
                                Brak aktywnych pojazdów w wybranej kategorii.
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
