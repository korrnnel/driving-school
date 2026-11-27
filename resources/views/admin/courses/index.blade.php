<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="h4 mb-0">Zarządzanie Kursami</h2>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary btn-sm">
                + Dodaj nowy kurs
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
            <form action="{{ route('admin.courses.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="category" class="form-label small text-muted mb-1">Kategoria</label>
                    <select class="form-select form-select-sm" id="category" name="category">
                        <option value="">Wszystkie kategorie</option>
                        <option value="A" {{ $category === 'A' ? 'selected' : '' }}>Kategoria A</option>
                        <option value="B" {{ $category === 'B' ? 'selected' : '' }}>Kategoria B</option>
                        <option value="C" {{ $category === 'C' ? 'selected' : '' }}>Kategoria C</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="type" class="form-label small text-muted mb-1">Typ kursu</label>
                    <select class="form-select form-select-sm" id="type" name="type">
                        <option value="">Wszystkie typy</option>
                        <option value="standard" {{ $type === 'standard' ? 'selected' : '' }}>Standardowy</option>
                        <option value="accelerated" {{ $type === 'accelerated' ? 'selected' : '' }}>Przyspieszony</option>
                        <option value="weekend" {{ $type === 'weekend' ? 'selected' : '' }}>Weekendowy</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filtruj</button>
                    @if($category || $type)
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary btn-sm">Wyczyść</a>
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
                            <th>Kategoria</th>
                            <th>Nazwa</th>
                            <th>Typ</th>
                            <th>Data rozpoczęcia</th>
                            <th class="text-end">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                            <tr>
                                <td>{{ $course->id }}</td>
                                <td><span class="badge bg-secondary">Kat. {{ $course->category }}</span></td>
                                <td>{{ $course->name }}</td>
                                <td>
                                    @if($course->type === 'standard') Standardowy
                                    @elseif($course->type === 'accelerated') Przyspieszony
                                    @else Weekendowy @endif
                                </td>
                                <td>{{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') : 'Brak daty' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-outline-primary">Edytuj</a>

                                    <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Czy na pewno chcesz usunąć ten kurs?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Usuń</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Brak kursów spełniających kryteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $courses->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-app-layout>
