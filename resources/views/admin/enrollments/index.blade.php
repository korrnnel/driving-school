<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="h4 mb-0">Zapisy na kursy</h2>
            <a href="{{ route('admin.enrollments.create') }}" class="btn btn-primary btn-sm">
                + Dodaj zapis
            </a>
        </div>
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

    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('admin.enrollments.index', array_filter(['search' => $search, 'category' => $category])) }}" class="btn btn-sm {{ $status === null ? 'btn-dark' : 'btn-outline-dark' }}">Wszystkie</a>
        <a href="{{ route('admin.enrollments.index', array_filter(['status' => 'waiting', 'search' => $search, 'category' => $category])) }}" class="btn btn-sm {{ $status === 'waiting' ? 'btn-warning text-dark fw-semibold' : 'btn-outline-warning text-dark' }}">
            Oczekujące
            @if($waitingCount > 0)
                <span class="badge bg-danger ms-1">{{ $waitingCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.enrollments.index', array_filter(['status' => 'in_progress', 'search' => $search, 'category' => $category])) }}" class="btn btn-sm {{ $status === 'in_progress' ? 'btn-primary' : 'btn-outline-primary' }}">W trakcie</a>
        <a href="{{ route('admin.enrollments.index', array_filter(['status' => 'completed', 'search' => $search, 'category' => $category])) }}" class="btn btn-sm {{ $status === 'completed' ? 'btn-success' : 'btn-outline-success' }}">Ukończone</a>
        <a href="{{ route('admin.enrollments.index', array_filter(['status' => 'dropped', 'search' => $search, 'category' => $category])) }}" class="btn btn-sm {{ $status === 'dropped' ? 'btn-secondary' : 'btn-outline-secondary' }}">Przerwane</a>
        <a href="{{ route('admin.enrollments.index', array_filter(['status' => 'cancelled', 'search' => $search, 'category' => $category])) }}" class="btn btn-sm {{ $status === 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">Anulowane</a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('admin.enrollments.index') }}" method="GET" class="row g-2 align-items-end">
                @if($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif
                <div class="col-md-5">
                    <label for="search" class="form-label small text-muted mb-1">Szukaj (kursant lub nr PKK)</label>
                    <input type="text" class="form-control form-control-sm" id="search" name="search" value="{{ $search }}" placeholder="Nazwisko lub PKK...">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label small text-muted mb-1">Kategoria kursu</label>
                    <select class="form-select form-select-sm" id="category" name="category">
                        <option value="">Wszystkie kategorie</option>
                        <option value="A" {{ $category === 'A' ? 'selected' : '' }}>Kategoria A</option>
                        <option value="B" {{ $category === 'B' ? 'selected' : '' }}>Kategoria B</option>
                        <option value="C" {{ $category === 'C' ? 'selected' : '' }}>Kategoria C</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filtruj</button>
                    @if($search !== '' || $category || $status)
                        <a href="{{ route('admin.enrollments.index') }}" class="btn btn-outline-secondary btn-sm">Wyczyść</a>
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
                            <th>ID</th>
                            <th>Kursant</th>
                            <th>Kurs</th>
                            <th>Data rozpoczęcia</th>
                            <th>Nr PKK</th>
                            <th>Status</th>
                            <th>Zapisano</th>
                            <th class="text-end">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enrollments as $enrollment)
                            <tr>
                                <td>{{ $enrollment->id }}</td>
                                <td><strong>{{ $enrollment->user->name }} {{ $enrollment->user->surname }}</strong></td>
                                <td>{{ $enrollment->course->name }}</td>
                                <td>{{ $enrollment->course->start_date ? \Carbon\Carbon::parse($enrollment->course->start_date)->format('d.m.Y') : 'Brak daty' }}</td>
                                <td><span class="font-monospace">{{ $enrollment->pkk_number ?? '-' }}</span></td>
                                <td>
                                    @if($enrollment->status === 'waiting')
                                        <span class="badge bg-warning text-dark">Oczekuje</span>
                                    @elseif($enrollment->status === 'in_progress')
                                        <span class="badge bg-primary">W trakcie</span>
                                    @elseif($enrollment->status === 'completed')
                                        <span class="badge bg-success">Ukończony</span>
                                    @elseif($enrollment->status === 'dropped')
                                        <span class="badge bg-secondary">Przerwany</span>
                                    @else
                                        <span class="badge bg-danger">Anulowany</span>
                                    @endif
                                </td>
                                <td>{{ $enrollment->enrolled_at->format('d.m.Y') }}</td>
                                <td class="text-end">
                                    @if($enrollment->status === 'waiting')
                                        <form action="{{ route('admin.enrollments.approve', $enrollment) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success">Zatwierdź</button>
                                        </form>

                                        <form action="{{ route('admin.enrollments.reject', $enrollment) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Czy na pewno chcesz odrzucić to zgłoszenie?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-warning text-dark">Odrzuć</button>
                                        </form>
                                    @endif

                                    <a href="{{ route('admin.enrollments.edit', $enrollment) }}" class="btn btn-sm btn-outline-primary">Edytuj</a>

                                    <form action="{{ route('admin.enrollments.destroy', $enrollment) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Czy na pewno chcesz usunąć ten zapis?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Usuń</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Brak zapisów spełniających kryteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $enrollments->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-app-layout>
