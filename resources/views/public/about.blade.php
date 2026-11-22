@extends('layouts.public')

@section('title', 'O nas - ' . config('app.name', 'OSK L-auto'))

@section('content')
    <section class="bg-white border-bottom py-4 mb-4">
        <div class="container">
            <h1 class="h2 fw-bold mb-2">O nas</h1>
            <p class="text-muted mb-0">Poznaj filozofię szkolenia i technologię wspierającą kursantów w {{ config('app.name', 'OSK L-auto') }}.</p>
        </div>
    </section>

    <section class="pb-5">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-3">Kim jesteśmy?</h4>
                            <p class="text-muted">
                                {{ config('app.name', 'OSK L-auto') }} to nowoczesny ośrodek szkolenia kierowców, który stawia na świadomą, bezpieczną jazdę oraz przejrzystą komunikację z kursantem na każdym etapie kursu.
                            </p>
                            <p class="text-muted mb-0">
                                Prowadzimy kursy standardowe, przyspieszone i weekendowe dla kategorii A, B oraz C. Naszym celem jest nie tylko zdanie egzaminu państwowego w WORD, ale przede wszystkim wyrobienie dobrych nawyków za kierownicą na całe życie.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-3">Innowacyjna analiza kohortowa</h4>
                            <p class="text-muted">
                                W odróżnieniu od tradycyjnych szkół jazdy, w {{ config('app.name', 'OSK L-auto') }} każda odbyta jazda jest mierzona i obiektywnie oceniana. Twój wynik nie jest oderwany od rzeczywistości – system porównuje go z wynikami innych kursantów tej samej kategorii, trybu kursu i na podobnym etapie wyjeżdżonych godzin.
                            </p>
                            <p class="text-muted mb-0">
                                Po zakończeniu kursu otrzymujesz pełną Kartę Absolwenta podsumowującą tempo szkolenia, średnią jakość jazdy oraz skuteczność na egzaminach.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">Jak działa nasz punktowy system oceny jazdy?</h4>
                    <p class="text-muted mb-4">
                        Maksymalna pula bazowa za realizację wszystkich zaplanowanych zadań podczas lekcji wynosi <strong>{{ $maxBase }} punktów</strong>. Od wyniku bazowego odejmowane są punkty karne według przejrzystych wag:
                    </p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 bg-light border rounded h-100">
                                <div class="fw-bold text-secondary mb-1">Błąd drobny (-{{ $penalties['minor_mistake'] }} pkt)</div>
                                <div class="small text-muted">
                                    Drobne potknięcia techniczne niestwarzające zagrożenia (np. zgaśnięcie silnika, zbyt późny kierunkowskaz, niepłynne hamowanie).
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light border rounded h-100">
                                <div class="fw-bold text-warning text-dark mb-1">Błąd krytyczny (-{{ $penalties['critical_mistake'] }} pkt)</div>
                                <div class="small text-muted">
                                    Poważne naruszenie przepisów lub sytuacja zagrażająca bezpieczeństwu (np. wymuszenie pierwszeństwa, przejazd na czerwonym świetle).
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light border rounded h-100">
                                <div class="fw-bold text-danger mb-1">Interwencja instruktora (-{{ $penalties['intervention'] }} pkt)</div>
                                <div class="small text-muted">
                                    Konieczność fizycznej reakcji instruktora (np. użycie dodatkowego hamulca lub korekta kierownicy) w celu uniknięcia zagrożenia.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
