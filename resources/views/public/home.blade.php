@extends('layouts.public')

@section('title', config('app.name', 'OSK L-auto') . ' - Strona główna')

@section('content')
    <section class="bg-primary text-white py-5">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">Zdobądź prawo jazdy pewnie i bez stresu</h1>
                    <p class="lead text-white-50 mb-4">
                        Nowoczesny ośrodek szkolenia kierowców {{ config('app.name', 'OSK L-auto') }}. Szkolimy na kategorie A, B i C z wykorzystaniem autorskiego systemu oceny postępów i analizy kohortowej.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('public.courses') }}" class="btn btn-light btn-lg fw-semibold">Zobacz ofertę kursów</a>
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Panel kursanta</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body py-4">
                            <div class="display-6 fw-bold text-primary">{{ $instructorsCount }}</div>
                            <div class="text-muted small text-uppercase fw-semibold mt-1">Instruktorów</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body py-4">
                            <div class="display-6 fw-bold text-success">{{ $vehiclesCount }}</div>
                            <div class="text-muted small text-uppercase fw-semibold mt-1">Aktywnych pojazdów</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body py-4">
                            <div class="display-6 fw-bold text-warning">{{ $completedLessonsCount }}</div>
                            <div class="text-muted small text-uppercase fw-semibold mt-1">Odbytych jazd</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body py-4">
                            <div class="display-6 fw-bold text-info">{{ $graduatesCount }}</div>
                            <div class="text-muted small text-uppercase fw-semibold mt-1">Absolwentów</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-5 border-top border-bottom">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Dlaczego {{ config('app.name', 'OSK L-auto') }}?</h2>
                <p class="text-muted">Łączymy doświadczenie szkoleniowe z nowoczesną analityką postępów kursanta.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-primary mb-2">Ocena każdej jazdy</h5>
                            <p class="text-muted mb-0">
                                Po każdej lekcji praktycznej instruktor zapisuje zrealizowane zadania, drobne i krytyczne błędy oraz interwencje, a system wylicza przejrzysty wynik punktowy od 0 do 100.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-success mb-2">Analiza kohortowa</h5>
                            <p class="text-muted mb-0">
                                Porównujemy Twoje wyniki z innymi kursantami z tej samej kategorii, trybu kursu i etapu godzinowego, dzięki czemu od razu wiesz, na czym się skupić przed egzaminem.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-dark mb-2">Dostęp online 24/7</h5>
                            <p class="text-muted mb-0">
                                W dedykowanym Panelu Kursanta sprawdzisz harmonogram nadchodzących jazd, postęp godzin, notatki instruktora, historię egzaminów oraz Kartę Absolwenta.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <h2 class="fw-bold mb-0">Najbliższe kursy</h2>
                <a href="{{ route('public.courses') }}" class="btn btn-outline-primary btn-sm">Zobacz pełną ofertę</a>
            </div>

            <div class="row g-4">
                @forelse ($upcomingCourses as $course)
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary">Kat. {{ $course->category }}</span>
                                    <span class="badge bg-secondary">
                                        @if($course->type === 'standard') Standardowy
                                        @elseif($course->type === 'accelerated') Przyspieszony
                                        @else Weekendowy @endif
                                    </span>
                                </div>
                                <h5 class="card-title fw-bold mt-2">{{ $course->name }}</h5>
                                <ul class="list-unstyled text-muted small mt-3 mb-4">
                                    <li class="mb-1"><strong>Start:</strong> {{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') : 'Termin wkrótce' }}</li>
                                    <li class="mb-1"><strong>Teoria:</strong> {{ $course->required_theory_hours }} h</li>
                                    <li><strong>Praktyka:</strong> {{ $course->required_practice_hours }} h</li>
                                </ul>
                                <div class="mt-auto">
                                    @guest
                                        <a href="{{ route('login', ['enroll' => 1]) }}" class="btn btn-outline-primary w-100 btn-sm">Zapisz się na kurs</a>
                                    @elseif(Auth::user()->role === 'student')
                                        @if(isset($studentEnrollmentsByCourse[$course->id]))
                                            @if($studentEnrollmentsByCourse[$course->id] === 'waiting')
                                                <button type="button" class="btn btn-warning w-100 btn-sm" disabled>Zgłoszenie oczekuje</button>
                                            @elseif(in_array($studentEnrollmentsByCourse[$course->id], ['in_progress', 'completed'], true))
                                                <button type="button" class="btn btn-success w-100 btn-sm" disabled>Jesteś już zapisany</button>
                                            @else
                                                <button type="button" class="btn btn-secondary w-100 btn-sm" disabled>Zapis archiwalny</button>
                                            @endif
                                        @elseif(in_array($course->category, $blockedCategories, true))
                                            <button type="button" class="btn btn-outline-secondary w-100 btn-sm" disabled>Masz już kurs Kat. {{ $course->category }}</button>
                                        @else
                                            <button type="button" class="btn btn-outline-primary w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#enrollModal" data-course-id="{{ $course->id }}" data-course-name="{{ $course->name }}">
                                                Zapisz się na kurs
                                            </button>
                                        @endif
                                    @endguest
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center py-4 text-muted">
                                Aktualnie przygotowujemy nowe terminy kursów. Skontaktuj się z naszym biurem!
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    @include('public.partials.enroll-modal')
@endsection
