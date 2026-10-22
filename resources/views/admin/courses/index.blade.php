<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
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
                                <td colspan="6" class="text-center py-4">Brak kursów w bazie danych.</td>
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
