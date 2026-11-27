<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="h4 mb-0">Zarządzanie Jazdami</h2>
            <a href="{{ route('admin.lessons.create') }}" class="btn btn-primary btn-sm">
                + Zaplanuj jazdę
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('admin.lessons.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="search" class="form-label small text-muted mb-1">Szukaj (kursant lub pojazd)</label>
                    <input type="text" class="form-control form-control-sm" id="search" name="search" value="{{ $search }}" placeholder="Nazwisko lub tablica...">
                </div>
                <div class="col-md-3">
                    <label for="instructor_id" class="form-label small text-muted mb-1">Instruktor</label>
                    <select class="form-select form-select-sm" id="instructor_id" name="instructor_id">
                        <option value="">Wszyscy instruktorzy</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}" {{ $instructorId === $instructor->id ? 'selected' : '' }}>
                                {{ $instructor->surname }} {{ $instructor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
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
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filtruj</button>
                    @if($search !== '' || $instructorId || $status || $date)
                        <a href="{{ route('admin.lessons.index') }}" class="btn btn-outline-secondary btn-sm">Wyczyść</a>
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
                            <th>Instruktor</th>
                            <th>Pojazd</th>
                            <th>Status</th>
                            <th class="text-end">Akcje</th>
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
                                    @if($lesson->is_additional_hour)
                                        <span class="badge bg-info text-dark ms-1">Dodatkowa</span>
                                    @endif
                                </td>
                                <td>{{ $lesson->instructor->name }} {{ $lesson->instructor->surname }}</td>
                                <td><span class="font-monospace">{{ $lesson->vehicle->registration_number }}</span></td>
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
                                    <a href="{{ route('admin.lessons.edit', $lesson) }}" class="btn btn-sm btn-outline-primary">Edytuj / Oceń</a>

                                    <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Czy na pewno chcesz usunąć tę jazdę?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Usuń</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Brak jazd spełniających kryteria.</td>
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
