@extends('layouts.public')

@section('title', 'Kontakt - ' . config('app.name', 'OSK L-auto'))

@section('content')
    <section class="bg-white border-bottom py-4 mb-4">
        <div class="container">
            <h1 class="h2 fw-bold mb-2">Kontakt i zapisy</h1>
            <p class="text-muted mb-0">Masz pytania lub chcesz zapisać się na kurs? Skontaktuj się z biurem {{ config('app.name', 'OSK L-auto') }}.</p>
        </div>
    </section>

    <section class="pb-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">Dane biura OSK</h4>

                            <div class="mb-3">
                                <div class="text-muted small text-uppercase fw-bold">Nazwa ośrodka</div>
                                <div class="fw-semibold">{{ config('app.name', 'OSK L-auto') }}</div>
                            </div>

                            <div class="mb-3">
                                <div class="text-muted small text-uppercase fw-bold">Adres biura</div>
                                <div>ul. Szkoleniowa 15, 35-001 Rzeszów</div>
                            </div>

                            <div class="mb-3">
                                <div class="text-muted small text-uppercase fw-bold">Telefon</div>
                                <div><a href="tel:+48111222333" class="text-decoration-none">+48 111 222 333</a></div>
                            </div>

                            <div class="mb-3">
                                <div class="text-muted small text-uppercase fw-bold">Adres e-mail</div>
                                <div><a href="mailto:kontakt@osk-lauto.pl" class="text-decoration-none">kontakt@osk-lauto.pl</a></div>
                            </div>

                            <div>
                                <div class="text-muted small text-uppercase fw-bold">Godziny otwarcia biura</div>
                                <div>Poniedziałek – Piątek: 08:00 – 17:00</div>
                                <div>Sobota: 09:00 – 13:00</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">Jak zapisać się na kurs?</h4>

                            <div class="mb-4">
                                <h6 class="fw-bold text-primary">Krok 1: Wyrobienie numeru PKK</h6>
                                <p class="text-muted small mb-0">
                                    Wykonaj badania lekarskie, zrób zdjęcie do prawa jazdy i złóż wniosek w swoim Wydziale Komunikacji, aby otrzymać 20-cyfrowy Profil Kandydata na Kierowcę (PKK).
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-primary">Krok 2: Założenie konta i kontakt z biurem</h6>
                                <p class="text-muted small mb-0">
                                    Zarejestruj się w naszym systemie lub skontaktuj telefonicznie/osobiście z biurem {{ config('app.name', 'OSK L-auto') }}, podając numer PKK oraz wybraną kategorię kursu.
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-primary">Krok 3: Start szkolenia i dostęp do analiz</h6>
                                <p class="text-muted small mb-0">
                                    Po potwierdzeniu zapisu przez biuro uzyskasz dostęp do swojego harmonogramu jazd, wyników z oceną punktową oraz porównania postępów z grupą w Panelu Kursanta.
                                </p>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                @guest
                                    <a href="{{ route('register') }}" class="btn btn-primary">Załóż konto kursanta</a>
                                @endguest
                                <a href="{{ route('public.courses') }}" class="btn btn-outline-primary">Sprawdź dostępne kursy</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
