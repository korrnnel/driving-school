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
                                    <a href="{{ route('instructor.lessons.evaluate', $lesson) }}" class="btn btn-sm btn-primary">
                                        {{ $lesson->status === 'scheduled' ? 'Zakończ i oceń' : 'Podgląd / Edytuj' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Brak przypisanych jazd.</td>
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
