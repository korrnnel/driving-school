<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Dodaj nowego użytkownika</h2>
    </x-slot>

    <div class="card shadow-sm max-w-2xl mx-auto">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Imię <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required maxlength="50">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <label for="surname" class="form-label">Nazwisko <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('surname') is-invalid @enderror" id="surname" name="surname" value="{{ old('surname') }}" required maxlength="50">
                        @error('surname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Adres e-mail <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required maxlength="255">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <label for="phone" class="form-label">Numer telefonu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required maxlength="15">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="role" class="form-label">Rola w systemie <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Kursant</option>
                            <option value="instructor" {{ old('role') == 'instructor' ? 'selected' : '' }}>Instruktor</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <label for="password" class="form-label">Hasło logowania <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required minlength="8">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div id="instructor-fields" class="p-3 bg-light border rounded mb-4" style="display: none;">
                    <h6 class="mb-3 text-primary">Dodatkowe dane instruktora</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="license_number" class="form-label">Numer uprawnień <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('license_number') is-invalid @enderror" id="license_number" name="license_number" value="{{ old('license_number') }}" maxlength="7">
                            @error('license_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <label for="assigned_vehicle_id" class="form-label">Przypisany pojazd</label>
                            <select class="form-select @error('assigned_vehicle_id') is-invalid @enderror" id="assigned_vehicle_id" name="assigned_vehicle_id">
                                <option value="">-- Brak przypisanego pojazdu --</option>
                                @foreach($availableVehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ old('assigned_vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->registration_number }} ({{ $vehicle->brand }} {{ $vehicle->model }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_vehicle_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Anuluj</a>
                    <button type="submit" class="btn btn-primary">Zapisz użytkownika</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const instructorFields = document.getElementById('instructor-fields');
            const licenseInput = document.getElementById('license_number');

            function toggleInstructorFields() {
                if (roleSelect.value === 'instructor') {
                    instructorFields.style.display = 'block';
                    licenseInput.setAttribute('required', 'required');
                } else {
                    instructorFields.style.display = 'none';
                    licenseInput.removeAttribute('required');
                }
            }

            roleSelect.addEventListener('change', toggleInstructorFields);
            toggleInstructorFields();
        });
    </script>
</x-app-layout>
