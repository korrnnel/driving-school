<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Zaplanuj nową jazdę</h2>
    </x-slot>

    <div class="card shadow-sm max-w-2xl mx-auto">
        <div class="card-body">
            <form action="{{ route('admin.lessons.store') }}" method="POST">
                @csrf

                <div class="mb-4 pb-3 border-bottom">
                    <h5 class="mb-3">Dane logistyczne</h5>

                    <div class="mb-3">
                        <label for="enrollment_id" class="form-label">Kursant (Zapis) <span class="text-danger">*</span></label>
                        <select class="form-select @error('enrollment_id') is-invalid @enderror" id="enrollment_id" name="enrollment_id" required>
                            <option value="">Wybierz aktywnego kursanta...</option>
                            @foreach($enrollments as $enrollment)
                                <option value="{{ $enrollment->id }}" {{ old('enrollment_id') == $enrollment->id ? 'selected' : '' }}>
                                    {{ $enrollment->user->surname }} {{ $enrollment->user->name }} - {{ $enrollment->course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('enrollment_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="instructor_id" class="form-label">Instruktor <span class="text-danger">*</span></label>
                            <select class="form-select @error('instructor_id') is-invalid @enderror" id="instructor_id" name="instructor_id" required>
                                <option value="">Wybierz instruktora...</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" data-vehicle="{{ $instructor->assigned_vehicle_id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->surname }} {{ $instructor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('instructor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mt-3 mt-md-0">
                            <label for="vehicle_id" class="form-label">Pojazd <span class="text-danger">*</span></label>
                            <select class="form-select @error('vehicle_id') is-invalid @enderror" id="vehicle_id" name="vehicle_id" required>
                                <option value="">Wybierz pojazd...</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->registration_number }} ({{ $vehicle->brand }} {{ $vehicle->model }})
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicle_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="mb-3">Termin i status</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label">Data jazdy <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', \Carbon\Carbon::tomorrow()->format('Y-m-d')) }}" required>
                            @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <label for="start_time" class="form-label">Godzina rozpoczęcia <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                            @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <label for="end_time" class="form-label">Godzina zakończenia <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                            @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Zaplanowana</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Zakończona</option>
                                <option value="no_show" {{ old('status') == 'no_show' ? 'selected' : '' }}>Nieobecność kursanta</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Odwołana</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0 d-flex align-items-end pb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_additional_hour" name="is_additional_hour" value="1" {{ old('is_additional_hour') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_additional_hour">
                                    Jazda dodatkowa (doszkalająca)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.lessons.index') }}" class="btn btn-secondary">Anuluj</a>
                    <button type="submit" class="btn btn-primary">Zaplanuj jazdę</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const instructorSelect = document.getElementById('instructor_id');
            const vehicleSelect = document.getElementById('vehicle_id');

            instructorSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const vehicleId = selectedOption.getAttribute('data-vehicle');

                if (vehicleId) {
                    vehicleSelect.value = vehicleId;
                }
            });
        });
    </script>
</x-app-layout>
