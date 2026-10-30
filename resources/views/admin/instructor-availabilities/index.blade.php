<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Grafik Instruktorów</h2>
            <a href="{{ route('admin.instructor-availabilities.create') }}" class="btn btn-primary btn-sm">
                + Dodaj godziny pracy
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
                            <th>Instruktor</th>
                            <th>Data</th>
                            <th>Godziny pracy</th>
                            <th class="text-end">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($availabilities as $availability)
                            <tr>
                                <td><strong>{{ $availability->instructor->name }} {{ $availability->instructor->surname }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($availability->date)->format('d.m.Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.instructor-availabilities.edit', $availability) }}" class="btn btn-sm btn-outline-primary">Edytuj</a>

                                    <form action="{{ route('admin.instructor-availabilities.destroy', $availability) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Czy na pewno chcesz usunąć ten wpis z grafiku?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Usuń</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Brak wpisów w grafiku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $availabilities->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-app-layout>
