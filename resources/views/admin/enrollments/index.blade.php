<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
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

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Kursant</th>
                            <th>Kurs</th>
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
                                <td><span class="font-monospace">{{ $enrollment->pkk_number ?? '-' }}</span></td>
                                <td>
                                    @if($enrollment->status === 'in_progress')
                                        <span class="badge bg-primary">W trakcie</span>
                                    @elseif($enrollment->status === 'completed')
                                        <span class="badge bg-success">Ukończony</span>
                                    @elseif($enrollment->status === 'dropped')
                                        <span class="badge bg-warning text-dark">Przerwany</span>
                                    @else
                                        <span class="badge bg-danger">Anulowany</span>
                                    @endif
                                </td>
                                <td>{{ $enrollment->enrolled_at->format('d.m.Y') }}</td>
                                <td class="text-end">
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
                                <td colspan="7" class="text-center py-4">Brak zapisów w bazie danych.</td>
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
