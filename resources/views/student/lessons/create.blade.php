<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="h4 mb-0">Umawianie jazd</h2>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">Powrót do Dashboardu</a>
        </div>
    </x-slot>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($eligibleEnrollments->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body py-5 text-center">
                <h5 class="fw-bold mb-2">Nie możesz jeszcze umawiać jazd</h5>
                <p class="text-muted mb-4">
                    Aby zarezerwować jazdę praktyczną, musisz posiadać aktywny kurs (zatwierdzony przez biuro) oraz zdany <strong>wewnętrzny egzamin z teorii</strong>.
                </p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">Sprawdź status swoich kursów</a>
            </div>
        </div>
    @else
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Krok 1: Wybierz kurs, instruktora i dzień</h5>
            </div>
            <div class="card-body">
                @if($willBeAdditionalHour)
                    <div class="alert alert-info small mb-3">
                        Wykorzystałeś już pełny limit obowiązkowych godzin w tym kursie. Kolejne rezerwacje będą automatycznie oznaczane jako <strong>jazda dodatkowa (doszkalająca)</strong>.
                    </div>
                @endif

                <form action="{{ route('student.lessons.create') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="enrollment_id" class="form-label">Twój kurs <span class="text-danger">*</span></label>
                            <select class="form-select" id="enrollment_id" name="enrollment_id" required onchange="this.form.submit()">
                                @foreach($eligibleEnrollments as $enrollment)
                                    <option value="{{ $enrollment->id }}" {{ $selectedEnrollment && $selectedEnrollment->id === $enrollment->id ? 'selected' : '' }}>
                                        {{ $enrollment->course->name }} (Kat. {{ $enrollment->course->category }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="instructor_id" class="form-label">Instruktor <span class="text-danger">*</span></label>
                            <select class="form-select" id="instructor_id" name="instructor_id" required>
                                <option value="">Wybierz instruktora...</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ $selectedInstructor && $selectedInstructor->id === $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->surname }} {{ $instructor->name }} ({{ $instructor->assignedVehicle->brand }} {{ $instructor->assignedVehicle->model }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="date" class="form-label">Data <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ $selectedDate }}" min="{{ $minDate }}" required>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Sprawdź terminy</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($searched && $selectedInstructor)
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold">
                        Krok 2: Wolne terminy ({{ \Carbon\Carbon::parse($selectedDate)->format('d.m.Y') }})
                    </h5>
                    <span class="text-muted small">
                        Instruktor: <strong>{{ $selectedInstructor->name }} {{ $selectedInstructor->surname }}</strong> |
                        Pojazd: <strong>{{ $selectedInstructor->assignedVehicle->brand }} {{ $selectedInstructor->assignedVehicle->model }}</strong>
                    </span>
                </div>
                <div class="card-body">
                    @if(count($availableSlots) > 0)
                        <div class="row g-3">
                            @foreach($availableSlots as $slot)
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                    <form action="{{ route('student.lessons.store') }}" method="POST" onsubmit="return confirm('Potwierdzasz rezerwację jazdy w dniu {{ \Carbon\Carbon::parse($selectedDate)->format('d.m.Y') }} w godzinach {{ $slot['start_time'] }} - {{ $slot['end_time'] }}?');">
                                        @csrf
                                        <input type="hidden" name="enrollment_id" value="{{ $selectedEnrollment->id }}">
                                        <input type="hidden" name="instructor_id" value="{{ $selectedInstructor->id }}">
                                        <input type="hidden" name="date" value="{{ $selectedDate }}">
                                        <input type="hidden" name="start_time" value="{{ $slot['start_time'] }}">
                                        <input type="hidden" name="end_time" value="{{ $slot['end_time'] }}">

                                        <button type="submit" class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center">
                                            <span class="fs-5 fw-bold">{{ $slot['start_time'] }} - {{ $slot['end_time'] }}</span>
                                            <span class="small mt-1">{{ $willBeAdditionalHour ? 'Zarezerwuj (dodatkowa)' : 'Zarezerwuj (2h)' }}</span>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            Brak wolnych 2-godzinnych terminów u wybranego instruktora w dniu <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('d.m.Y') }}</strong>. Wybierz inny dzień (np. dzień roboczy) lub innego instruktora.
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endif
</x-app-layout>
