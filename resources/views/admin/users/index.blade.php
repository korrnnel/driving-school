<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Zarządzanie Użytkownikami</h2>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                + Dodaj użytkownika
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

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Imię i Nazwisko</th>
                            <th>Email / Telefon</th>
                            <th>Rola</th>
                            <th>Info Dodatkowe</th>
                            <th class="text-end">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td><strong>{{ $user->name }} {{ $user->surname }}</strong></td>
                                <td>
                                    <div>{{ $user->email }}</div>
                                    <small class="text-muted">{{ $user->phone }}</small>
                                </td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-danger">Administrator</span>
                                    @elseif($user->role === 'instructor')
                                        <span class="badge bg-primary">Instruktor</span>
                                    @else
                                        <span class="badge bg-success">Kursant</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->role === 'instructor')
                                        <div>Upraw: <span class="font-monospace">{{ $user->license_number }}</span></div>
                                        @if($user->assignedVehicle)
                                            <small class="text-muted">Auto: {{ $user->assignedVehicle->registration_number }}</small>
                                        @else
                                            <small class="text-danger">Brak pojazdu</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">Edytuj</a>

                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Czy na pewno chcesz usunąć tego użytkownika?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" {{ auth()->id() === $user->id ? 'disabled' : '' }}>Usuń</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Brak użytkowników w bazie danych.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-app-layout>
