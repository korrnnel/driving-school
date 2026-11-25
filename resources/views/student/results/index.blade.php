<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="h4 mb-0">Moje wyniki i analiza kohortowa</h2>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                Powrót do Dashboardu
            </a>
        </div>
    </x-slot>

    @forelse ($courseReports as $report)
        <div class="card shadow-sm mb-5">
            <div
                class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-light text-dark me-2">Kat. {{ $report['enrollment']->course->category }}</span>
                    <strong class="fs-5">{{ $report['enrollment']->course->name }}</strong>
                </div>
                <div>
                    @if ($report['enrollment']->status === 'waiting')
                        <span class="badge bg-warning text-dark">Oczekuje na akceptację</span>
                    @elseif($report['enrollment']->status === 'in_progress')
                        <span class="badge bg-primary">W trakcie</span>
                    @elseif($report['enrollment']->status === 'completed')
                        <span class="badge bg-success">Ukończony</span>
                    @elseif($report['enrollment']->status === 'dropped')
                        <span class="badge bg-secondary">Przerwany</span>
                    @else
                        <span class="badge bg-danger">Anulowany</span>
                    @endif
                </div>
            </div>

            <div class="card-body">
                @if ($report['graduation_analysis'])
                    <div class="p-3 p-md-4 bg-success bg-opacity-10 border border-success rounded mb-4">
                        <h5 class="text-success fw-bold mb-3">Karta Absolwenta - Podsumowanie końcowe</h5>
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="text-muted small text-uppercase fw-bold mb-1">Czas trwania kursu
                                        </div>
                                        <div class="h4 fw-bold mb-1">
                                            {{ $report['graduation_analysis']['duration_days'] !== null ? $report['graduation_analysis']['duration_days'] . ' dni' : 'Brak danych' }}
                                        </div>
                                        @if ($report['graduation_analysis']['cohort_avg_duration'] !== null)
                                            <div class="small text-muted">
                                                Średnia grupy:
                                                {{ $report['graduation_analysis']['cohort_avg_duration'] }} dni.
                                                @if ($report['graduation_analysis']['duration_diff_percent'] > 0)
                                                    <span class="text-success fw-semibold">Jesteś szybszy o
                                                        {{ $report['graduation_analysis']['duration_diff_percent'] }}%!</span>
                                                @elseif($report['graduation_analysis']['duration_diff_percent'] < 0)
                                                    <span>Dłużej o
                                                        {{ abs($report['graduation_analysis']['duration_diff_percent']) }}%
                                                        od średniej.</span>
                                                @else
                                                    <span>Dokładnie średnia grupy.</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="text-muted small text-uppercase fw-bold mb-1">Skuteczność w WORD -
                                            Egzamin praktyczny</div>
                                        <div class="h4 fw-bold mb-1">
                                            @if ($report['graduation_analysis']['word_passed'])
                                                Zdane za {{ $report['graduation_analysis']['word_attempts'] }}. razem
                                            @elseif($report['graduation_analysis']['word_attempts'] > 0)
                                                Podejścia: {{ $report['graduation_analysis']['word_attempts'] }}
                                            @else
                                                Brak wpisów
                                            @endif
                                        </div>
                                        @if ($report['graduation_analysis']['cohort_first_try_rate'] !== null)
                                            <div class="small text-muted">
                                                {{ $report['graduation_analysis']['cohort_first_try_rate'] }}% kursantów
                                                w Twojej grupie zdało za 1. razem.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="text-muted small text-uppercase fw-bold mb-1">Godziny dodatkowe
                                        </div>
                                        <div class="h4 fw-bold mb-1">
                                            {{ $report['graduation_analysis']['extra_hours'] }} h</div>
                                        <div class="small text-muted">
                                            Średnia w Twojej grupie:
                                            {{ $report['graduation_analysis']['cohort_avg_extra_hours'] }} h.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="text-muted small text-uppercase fw-bold mb-1">Najlepszy wynik z
                                            jazdy</div>
                                        <div class="h4 fw-bold text-primary mb-1">
                                            {{ $report['graduation_analysis']['best_score'] !== null ? $report['graduation_analysis']['best_score'] . '/100 pkt' : 'Brak danych' }}
                                        </div>
                                        @if ($report['graduation_analysis']['cohort_avg_best_score'] !== null)
                                            <div class="small text-muted">
                                                Średni najlepszy wynik w grupie:
                                                {{ $report['graduation_analysis']['cohort_avg_best_score'] }}/100 pkt.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-lg-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="text-muted small text-uppercase fw-bold mb-1">Średnia jakość jazdy
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="h4 fw-bold mb-0">
                                                {{ $report['graduation_analysis']['average_score'] !== null ? $report['graduation_analysis']['average_score'] . '/100 pkt' : 'Brak danych' }}
                                            </span>
                                            @if ($report['graduation_analysis']['top_percent'] !== null)
                                                <span class="badge bg-success">Top
                                                    {{ $report['graduation_analysis']['top_percent'] }}% grupy</span>
                                            @endif
                                        </div>
                                        @if ($report['graduation_analysis']['cohort_avg_score'] !== null)
                                            <div class="small text-muted">
                                                Średnia ogólna Twojej grupy:
                                                {{ $report['graduation_analysis']['cohort_avg_score'] }}/100 pkt.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($report['latest_analysis'] && $report['latest_lesson'])
                    <div
                        class="alert {{ $report['latest_analysis']['cohort_average'] !== null && $report['latest_analysis']['score'] >= $report['latest_analysis']['cohort_average'] + 5 ? 'alert-success' : ($report['latest_analysis']['cohort_average'] !== null && $report['latest_analysis']['score'] <= $report['latest_analysis']['cohort_average'] - 5 ? 'alert-warning' : 'alert-primary') }} mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                            <h6 class="fw-bold mb-0">Ocena najnowszej jazdy
                                ({{ $report['latest_lesson']->date->format('d.m.Y') }})</h6>
                            <span class="badge bg-dark fs-6">{{ $report['latest_analysis']['score'] }} / 100 pkt</span>
                        </div>
                        <p class="mb-0">{{ $report['latest_analysis']['message'] }}</p>
                    </div>
                @endif

                <h6 class="fw-bold mb-3">Historia zakończonych jazd</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-hover table-striped align-middle mb-0 text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Data i czas</th>
                                <th>Instruktor</th>
                                <th>Zadania</th>
                                <th>Błędy (D / K / I)</th>
                                <th>Wynik</th>
                                <th>Notatka</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($report['lessons_with_scores'] as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item['lesson']->date->format('d.m.Y') }}</strong>
                                        <small class="d-block text-muted">
                                            {{ substr($item['lesson']->start_time, 0, 5) }} -
                                            {{ substr($item['lesson']->end_time, 0, 5) }}
                                            @if ($item['lesson']->is_additional_hour)
                                                <span class="badge bg-info text-dark ms-1">Dodatkowa</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>{{ $item['lesson']->instructor->name }}
                                        {{ $item['lesson']->instructor->surname }}</td>
                                    <td>{{ $item['lesson']->tasks_completed_count }} /
                                        {{ $item['lesson']->tasks_planned_count }}</td>
                                    <td>
                                        <span class="badge bg-secondary"
                                            title="Błędy drobne">{{ $item['lesson']->minor_mistakes_count }}</span>
                                        <span class="badge bg-warning text-dark"
                                            title="Błędy krytyczne">{{ $item['lesson']->critical_mistakes_count }}</span>
                                        <span class="badge bg-danger"
                                            title="Interwencje">{{ $item['lesson']->interventions_count }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $item['score'] >= 80 ? 'bg-success' : ($item['score'] >= 50 ? 'bg-primary' : 'bg-danger') }} fs-6">
                                            {{ $item['score'] }} / 100
                                        </span>
                                    </td>
                                    <td class="text-wrap" style="min-width: 200px;">
                                        <small
                                            class="text-muted">{{ $item['lesson']->instructor_notes ?? '-' }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">Brak zakończonych jazd w tym
                                        kursie.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <h6 class="fw-bold mb-3">Historia egzaminów</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-striped mb-0 text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Data</th>
                                <th>Typ</th>
                                <th>Kategoria</th>
                                <th>Egzaminator</th>
                                <th>Wynik</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($report['exams'] as $exam)
                                <tr>
                                    <td><strong>{{ $exam->date->format('d.m.Y') }}</strong></td>
                                    <td>
                                        @if ($exam->type === 'internal')
                                            <span class="badge bg-secondary">Wewnętrzny</span>
                                        @else
                                            <span class="badge bg-primary">Państwowy (WORD)</span>
                                        @endif
                                    </td>
                                    <td>{{ $exam->category === 'theory' ? 'Teoria' : 'Praktyka' }}</td>
                                    <td>{{ $exam->instructor ? $exam->instructor->name . ' ' . $exam->instructor->surname : '-' }}
                                    </td>
                                    <td>
                                        @if ($exam->result === 'passed')
                                            <span class="badge bg-success">Zdał(a)</span>
                                        @else
                                            <span class="badge bg-danger">Nie zdał(a)</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">Brak zarejestrowanych
                                        egzaminów dla tego kursu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="card shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                Brak zapisów na kursy do wyświetlenia.
            </div>
        </div>
    @endforelse
</x-app-layout>
