<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Zarządzanie Pojazdami</h2>
            <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary btn-sm">
                + Dodaj pojazd
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
                            <th>Marka i Model</th>
                            <th>Nr Rejestracyjny</th>
                            <th>Status</th>
                            <th class="text-end">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicles as $vehicle)
                            <tr>
                                <td>{{ $vehicle->id }}</td>
                                <td><span class="badge bg-secondary">Kat. {{ $vehicle->category }}</span></td>
                                <td>{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                                <td><span class="font-monospace">{{ $vehicle->registration_number }}</span></td>
                                <td>
                                    @if($vehicle->status === 'active')
                                        <span class="badge bg-success">Aktywny</span>
                                    @elseif($vehicle->status === 'maintenance')
                                        <span class="badge bg-warning text-dark">W serwisie</span>
                                    @else
                                        <span class="badge bg-danger">Wycofany</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="btn btn-sm btn-outline-primary">Edytuj</a>

                                    <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Czy na pewno chcesz usunąć ten pojazd?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Usuń</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Brak pojazdów w bazie danych.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $vehicles->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-app-layout>
