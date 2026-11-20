<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="h4 mb-0">Panel Kursanta - Moje Kursy</h2>
            <a href="{{ route('student.results.index') }}" class="btn btn-primary btn-sm">
                Zobacz wyniki i analizę
            </a>
        </div>
    </x-slot>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body py-4">
                    <h4 class="mb-1">Witaj, {{ Auth::user()->name }}!</h4>
                    <p class="mb-0 text-white-50">Twoja rola w systemie: <strong>{{ Auth::user()->role_translated }}</strong>. Poniżej znajdziesz status swoich kursów oraz zaplanowane jazdy.</p>
                </div>
            </div>
        </div>
    </div>

    @forelse ($studentCourses as $item)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-secondary me-2">Kat. {{ $item['enrollment']->course->category }}</span>
                    <strong class="fs-5">{{ $item['enrollment']->course->name }}</strong>
                </div>
                <div>
                    @if($item['enrollment']->status === 'in_progress')
                        <span class="badge bg-primary">W trakcie</span>
                    @elseif($item['enrollment']->status === 'completed')
                        <span class="badge bg-success">Ukończony</span>
                    @elseif($item['enrollment']->status === 'dropped')
                        <span class="badge bg-warning text-dark">Przerwany</span>
                    @else
                        <span class="badge bg-danger">Anulowany</span>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="text-muted small">Data zapisu</div>
                        <div class="fw-semibold">{{ $item['enrollment']->enrolled_at->format('d.m.Y') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Numer PKK</div>
                        <div class="font-monospace">{{ $item['enrollment']->pkk_number ?? 'Brak' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Teoria</div>
                        <div>
                            @if($item['enrollment']->theory_completed_at)
                                <span class="badge bg-success">Zaliczona ({{ $item['enrollment']->theory_completed_at->format('d.m.Y') }})</span>
                            @else
                                <span class="badge bg-secondary">W trakcie</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-semibold">Postęp jazd praktycznych</span>
                        <span><strong>{{ $item['driven_hours'] }} / {{ $item['required_hours'] }} h</strong> ({{ $item['progress_percent'] }}%)</span>
                    </div>
                    <div class="progress" style="height: 12px;">
                        <div class="progress-bar {{ $item['progress_percent'] >= 100 ? 'bg-success' : 'bg-primary' }}" role="progressbar" style="width: {{ $item['progress_percent'] }}%;" aria-valuenow="{{ $item['progress_percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                @if($item['enrollment']->status === 'completed')
                    <div class="alert alert-success d-flex justify-content-between align-items-center flex-wrap gap-2 mb-0">
                        <div>
                            <strong>Gratulacje!</strong> Ukończyłeś ten kurs. Sprawdź swoją Kartę Absolwenta i porównanie na tle grupy.
                        </div>
                        <a href="{{ route('student.results.index') }}" class="btn btn-success btn-sm">
                            Karta Absolwenta
                        </a>
                    </div>
                @elseif($item['upcoming_lessons']->isNotEmpty())
                    <h6 class="fw-bold mb-3">Nadchodzące zaplanowane jazdy</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Data</th>
                                    <th>Godziny</th>
                                    <th>Instruktor</th>
                                    <th>Pojazd</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item['upcoming_lessons'] as $lesson)
                                    <tr>
                                        <td><strong>{{ $lesson->date->format('d.m.Y') }}</strong></td>
                                        <td>{{ substr($lesson->start_time, 0, 5) }} - {{ substr($lesson->end_time, 0, 5) }}</td>
                                        <td>{{ $lesson->instructor->name }} {{ $lesson->instructor->surname }}</td>
                                        <td>
                                            {{ $lesson->vehicle->brand }} {{ $lesson->vehicle->model }}
                                            <span class="font-monospace text-muted">({{ $lesson->vehicle->registration_number }})</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-muted small">Brak nadchodzących zaplanowanych jazd dla tego kursu.</div>
                @endif
            </div>
        </div>
    @empty
        <div class="card shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                Nie jesteś jeszcze zapisany na żaden kurs.
            </div>
        </div>
    @endforelse
</x-app-layout>
