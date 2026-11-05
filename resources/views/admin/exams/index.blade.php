<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
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
                                <td>
                                    @if($exam->category === 'theory')
                                        Teoria
                                    @else
                                        Praktyka
                                    @endif
                                </td>
                                <td>
                                    @if($exam->result === 'passed')
                                        <span class="badge bg-success">Pozytywny</span>
                                    @else
                                        <span class="badge bg-danger">Negatywny</span>
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
                                <td colspan="7" class="text-center py-4">Brak egzaminów w bazie danych.</td>
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
