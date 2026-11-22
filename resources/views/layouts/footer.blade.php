<footer class="bg-dark text-white pt-5 mt-auto">
    <div class="container pb-4">
        <div class="row gy-4">
            <div class="col-md-4">
                <h6 class="text-uppercase fw-bold mb-2">{{ config('app.name', 'OSK L-auto') }}</h6>
                <hr class="mb-3 mt-0 d-inline-block border-0" style="width: 60px; background-color: #0d6efd; height: 2px; opacity: 1;">
                <p class="small text-white-50 mb-0">
                    Nowoczesny ośrodek szkolenia kierowców na kategorie A, B oraz C. Uczymy bezpiecznej i pewnej jazdy z wykorzystaniem systemu analizy postępów kursanta.
                </p>
            </div>

            <div class="col-md-4">
                <h6 class="text-uppercase fw-bold mb-2">Godziny otwarcia</h6>
                <hr class="mb-3 mt-0 d-inline-block border-0" style="width: 60px; background-color: #0d6efd; height: 2px; opacity: 1;">
                <div class="small text-white-50">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Poniedziałek - Piątek:</span>
                        <span>08:00 - 17:00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sobota:</span>
                        <span>09:00 - 13:00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Niedziela:</span>
                        <span>Nieczynne</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <h6 class="text-uppercase fw-bold mb-2">Kontakt</h6>
                <hr class="mb-3 mt-0 d-inline-block border-0" style="width: 60px; background-color: #0d6efd; height: 2px; opacity: 1;">
                <ul class="list-unstyled small text-white-50 mb-0">
                    <li class="mb-2 d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="text-white flex-shrink-0" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                        </svg>
                        <span>ul. Szkoleniowa 15, 35-001 Rzeszów, PL</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="text-white flex-shrink-0" viewBox="0 0 16 16">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                        </svg>
                        <a href="mailto:kontakt@osk-lauto.pl" class="text-white-50 text-decoration-none">kontakt@osk-lauto.pl</a>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="text-white flex-shrink-0" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                        </svg>
                        <a href="tel:+48111222333" class="text-white-50 text-decoration-none">+48 111 222 333</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="text-center py-3 small text-white-50" style="background-color: rgba(0, 0, 0, 0.25);">
        &copy; {{ date('Y') }} Copyright:
        <a class="text-white text-decoration-underline" href="{{ route('home') }}">{{ config('app.name', 'OSK L-auto') }}</a>
    </div>
</footer>
