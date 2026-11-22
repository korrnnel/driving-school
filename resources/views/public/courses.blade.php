@extends('layouts.public')

@section('title', 'Oferta kursów - ' . config('app.name', 'OSK L-auto'))

@section('content')
    <section class="bg-white border-bottom py-4 mb-4">
        <div class="container">
            <h1 class="h2 fw-bold mb-2">Oferta kursów</h1>
            <p class="text-muted mb-3">Wybierz kategorię i sprawdź aktualne terminy nadchodzących szkoleń w {{ config('app.name', 'OSK L-auto') }}.</p>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('public.courses') }}" class="btn btn-sm {{ $category === null ? 'btn-primary' : 'btn-outline-primary' }}">Wszystkie</a>
                <a href="{{ route('public.courses', ['category' => 'A']) }}" class="btn btn-sm {{ $category === 'A' ? 'btn-primary' : 'btn-outline-primary' }}">Kategoria A</a>
                <a href="{{ route('public.courses', ['category' => 'B']) }}" class="btn btn-sm {{ $category === 'B' ? 'btn-primary' : 'btn-outline-primary' }}">Kategoria B</a>
                <a href="{{ route('public.courses', ['category' => 'C']) }}" class="btn btn-sm {{ $category === 'C' ? 'btn-primary' : 'btn-outline-primary' }}">Kategoria C</a>
            </div>
        </div>
    </section>

    <section class="pb-5">
        <div class="container">
            <div class="row g-4">
                @forelse ($courses as $course)
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body d-flex flex-column p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-primary fs-6">Kat. {{ $course->category }}</span>
                                    <span class="badge bg-light text-dark border">
                                        @if($course->type === 'standard') Standardowy
                                        @elseif($course->type === 'accelerated') Przyspieszony
                                        @else Weekendowy @endif
                                    </span>
                                </div>

                                <h5 class="card-title fw-bold mb-3">{{ $course->name }}</h5>

                                <ul class="list-group list-group-flush mb-4 small">
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span class="text-muted">Data rozpoczęcia</span>
                                        <strong>{{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') : 'Zbieramy grupę' }}</strong>
                                    </li>
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span class="text-muted">Godziny teorii</span>
                                        <strong>{{ $course->required_theory_hours }} h</strong>
                                    </li>
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span class="text-muted">Godziny praktyki</span>
                                        <strong>{{ $course->required_practice_hours }} h</strong>
                                    </li>
                                </ul>

                                <div class="mt-auto">
                                    <a href="{{ route('public.contact') }}" class="btn btn-primary w-100">Zapytaj o zapis</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center py-5 text-muted">
                                Brak dostępnych nadchodzących kursów dla wybranej kategorii.
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
