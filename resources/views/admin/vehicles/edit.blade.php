<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Edytuj pojazd: {{ $vehicle->brand }} {{ $vehicle->model }}</h2>
    </x-slot>

    <div class="card shadow-sm max-w-2xl mx-auto">
        <div class="card-body">
            <form action="{{ route('admin.vehicles.update', $vehicle) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="category" class="form-label">Kategoria <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="A" {{ old('category', $vehicle->category) == 'A' ? 'selected' : '' }}>Kategoria A</option>
                            <option value="B" {{ old('category', $vehicle->category) == 'B' ? 'selected' : '' }}>Kategoria B</option>
                            <option value="C" {{ old('category', $vehicle->category) == 'C' ? 'selected' : '' }}>Kategoria C</option>
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status', $vehicle->status) == 'active' ? 'selected' : '' }}>Aktywny</option>
                            <option value="maintenance" {{ old('status', $vehicle->status) == 'maintenance' ? 'selected' : '' }}>W serwisie</option>
                            <option value="out_of_service" {{ old('status', $vehicle->status) == 'out_of_service' ? 'selected' : '' }}>Wycofany z użytku</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="brand" class="form-label">Marka <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand', $vehicle->brand) }}" required maxlength="50">
                        @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <label for="model" class="form-label">Model <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" value="{{ old('model', $vehicle->model) }}" required maxlength="50">
                        @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="registration_number" class="form-label">Numer rejestracyjny <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('registration_number') is-invalid @enderror" id="registration_number" name="registration_number" value="{{ old('registration_number', $vehicle->registration_number) }}" required maxlength="10">
                    @error('registration_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">Anuluj</a>
                    <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
