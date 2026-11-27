<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Mój harmonogram jazd</h2>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('instructor.lessons.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="search" class="form-label small text-muted mb-1">Szukaj kursanta</label>
                    <input type="text" class="form-control form-control-sm" id="search" name="search" value="{{ $search }}" placeholder="Imię lub nazwisko...">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label small text-muted mb-1">Status</label>
                    <select class="form-select form-select-sm" id="status" name="status">
                        <option value="">Wszystkie</option>
                        <option value="scheduled" {{ $status === 'scheduled' ? 'selected' : '' }}>Zaplanowana</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Zakończona</option>
                        <option value="no_show" {{ $status === 'no_show' ? 'selected' : '' }}>Nieobecność</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Odwołana</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date" class="form-label small text-muted mb-1">Data</label>
                    <input type="date" class="form-control form-control-sm" id="date" name="date" value="{{ $date }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filtruj</button>
                    @if($search !== '' || $status || $date)
                        <a href="{{ route('instructor.lessons.index') }}" class="btn btn-outline-secondary btn-sm">Wyczyść</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th>Data i Czas</th>
                            <th>Kursant</th>
                            <th>Status</th>
                            <th class="text-end">Akcja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lessons as $lesson)
                            <tr>
                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($lesson->date)->format('d.m.Y') }}</strong><br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($lesson->end_time)->format('H:i') }}</small>
                                </td>
                                <td>
                                    {{ $lesson->enrollment->user->name }} {{ $lesson->enrollment->user->surname }}
                                </td>
                                <td>
                                    @if($lesson->status === 'scheduled')
                                        <span class="badge bg-primary">Zaplanowana</span>
                                    @elseif($lesson->status === 'completed')
                                        <span class="badge bg-success">Zakończona</span>
                                    @elseif($lesson->status === 'no_show')
                                        <span class="badge bg-warning text-dark">Nieobecność</span>
                                    @else
                                        <span class="badge bg-danger">Odwołana</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if(!$lesson->can_evaluate)
                                        <span class="text-muted small px-2">Dostępne po rozpoczęciu</span>
                                    @else
                                        <a href="{{ route('instructor.lessons.evaluate', $lesson) }}" class="btn btn-sm btn-primary">
                                            {{ $lesson->status === 'scheduled' ? 'Zakończ i oceń' : 'Podgląd / Edytuj' }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Brak jazd spełniających kryteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $lessons->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-app-layout>
