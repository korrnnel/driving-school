<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="h4 mb-0">Zarządzanie Egzaminami</h2>
            <a href="{{ route('admin.exams.create') }}" class="btn btn-primary btn-sm">
                + Dodaj egzamin
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
            <form action="{{ route('admin.exams.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="search" class="form-label small text-muted mb-1">Szukaj kursanta</label>
                    <input type="text" class="form-control form-control-sm" id="search" name="search" value="{{ $search }}" placeholder="Imię lub nazwisko...">
                </div>
                <div class="col-md-2">
                    <label for="type" class="form-label small text-muted mb-1">Typ</label>
                    <select class="form-select form-select-sm" id="type" name="type">
                        <option value="">Wszystkie</option>
                        <option value="internal" {{ $type === 'internal' ? 'selected' : '' }}>Wewnętrzny</option>
                        <option value="state" {{ $type === 'state' ? 'selected' : '' }}>Państwowy (WORD)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="category" class="form-label small text-muted mb-1">Kategoria</label>
                    <select class="form-select form-select-sm" id="category" name="category">
                        <option value="">Wszystkie</option>
                        <option value="theory" {{ $category === 'theory' ? 'selected' : '' }}>Teoria</option>
                        <option value="practice" {{ $category === 'practice' ? 'selected' : '' }}>Praktyka</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="result" class="form-label small text-muted mb-1">Wynik</label>
                    <select class="form-select form-select-sm" id="result" name="result">
                        <option value="">Wszystkie</option>
                        <option value="passed" {{ $result === 'passed' ? 'selected' : '' }}>Zdał(a)</option>
                        <option value="failed" {{ $result === 'failed' ? 'selected' : '' }}>Nie zdał(a)</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filtruj</button>
                    @if($search !== '' || $type || $category || $result)
                        <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-secondary btn-sm">Wyczyść</a>
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
                            <th>Data</th>
                            <th>Kursant (Zapis)</th>
                            <th>Typ egzaminu</th>
                            <th>Kategoria</th>
                            <th>Wynik</th>
                            <th>Egzaminator (Szkoła)</th>
                            <th class="text-end">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($exams as $exam)
                            <tr>
                                <td><strong>{{ \Carbon\Carbon::parse($exam->date)->format('d.m.Y') }}</strong></td>
                                <td>
                                    {{ $exam->enrollment->user->name }} {{ $exam->enrollment->user->surname }}
                                    <small class="d-block text-muted">{{ $exam->enrollment->course->name }}</small>
                                </td>
                                <td>
                                    @if($exam->type === 'internal')
                                        <span class="badge bg-secondary">Wewnętrzny</span>
                                    @else
                                        <span class="badge bg-primary">Państwowy (WORD)</span>
                                    @endif
                                </td>
                                <td>{{ $exam->category === 'theory' ? 'Teoria' : 'Praktyka' }}</td>
                                <td>
                                    @if($exam->result === 'passed')
                                        <span class="badge bg-success">Zdał(a)</span>
                                    @else
                                        <span class="badge bg-danger">Nie zdał(a)</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $exam->instructor ? $exam->instructor->name . ' ' . $exam->instructor->surname : '-' }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.exams.edit', $exam) }}" class="btn btn-sm btn-outline-primary">Edytuj</a>

                                    <form action="{{ route('admin.exams.destroy', $exam) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Czy na pewno chcesz usunąć ten egzamin?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Usuń</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Brak egzaminów spełniających kryteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $exams->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-app-layout>
