<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Edytuj jazdę</h2>
    </x-slot>

    <div class="card shadow-sm max-w-2xl mx-auto">
        <div class="card-body">
            <form action="{{ route('admin.lessons.update', $lesson) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4 pb-3 border-bottom">
                    <h5 class="mb-3 text-primary">Dane logistyczne</h5>

                    <div class="mb-3">
                        <label for="enrollment_id" class="form-label">Kursant (Zapis) <span class="text-danger">*</span></label>
                        <select class="form-select @error('enrollment_id') is-invalid @enderror" id="enrollment_id" name="enrollment_id" required>
                            @foreach($enrollments as $enrollment)
                                <option value="{{ $enrollment->id }}" {{ old('enrollment_id', $lesson->enrollment_id) == $enrollment->id ? 'selected' : '' }}>
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
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" data-vehicle="{{ $instructor->assigned_vehicle_id }}" {{ old('instructor_id', $lesson->instructor_id) == $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->surname }} {{ $instructor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('instructor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mt-3 mt-md-0">
                            <label for="vehicle_id" class="form-label">Pojazd <span class="text-danger">*</span></label>
                            <select class="form-select @error('vehicle_id') is-invalid @enderror" id="vehicle_id" name="vehicle_id" required>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $lesson->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->registration_number }} ({{ $vehicle->brand }} {{ $vehicle->model }})
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicle_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label">Data jazdy <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $lesson->date->format('Y-m-d')) }}" required>
                            @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <label for="start_time" class="form-label">Rozpoczęcie <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($lesson->start_time)->format('H:i')) }}" required>
                            @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <label for="end_time" class="form-label">Zakończenie <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($lesson->end_time)->format('H:i')) }}" required>
                            @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="scheduled" {{ old('status', $lesson->status) == 'scheduled' ? 'selected' : '' }}>Zaplanowana</option>
                                <option value="completed" {{ old('status', $lesson->status) == 'completed' ? 'selected' : '' }}>Zakończona</option>
                                <option value="no_show" {{ old('status', $lesson->status) == 'no_show' ? 'selected' : '' }}>Nieobecność kursanta</option>
                                <option value="cancelled" {{ old('status', $lesson->status) == 'cancelled' ? 'selected' : '' }}>Odwołana</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0 d-flex align-items-end pb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_additional_hour" name="is_additional_hour" value="1" {{ old('is_additional_hour', $lesson->is_additional_hour) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_additional_hour">Jazda dodatkowa (doszkalająca)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="mb-3 text-success">Ocena jazdy kursanta</h5>
                    <div class="form-text mb-3">Ta sekcja powinna być wypełniana przez instruktora po zakończonej jeździe. Służy do monitorowania postępów i analizy.</div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tasks_planned_count" class="form-label">Zadania zaplanowane</label>
                            <input type="number" class="form-control @error('tasks_planned_count') is-invalid @enderror" id="tasks_planned_count" name="tasks_planned_count" value="{{ old('tasks_planned_count', $lesson->tasks_planned_count) }}" min="0">
                            @error('tasks_planned_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <label for="tasks_completed_count" class="form-label">Zadania zrealizowane</label>
                            <input type="number" class="form-control @error('tasks_completed_count') is-invalid @enderror" id="tasks_completed_count" name="tasks_completed_count" value="{{ old('tasks_completed_count', $lesson->tasks_completed_count) }}" min="0">
                            @error('tasks_completed_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="interventions_count" class="form-label">Interwencje</label>
                            <input type="number" class="form-control @error('interventions_count') is-invalid @enderror" id="interventions_count" name="interventions_count" value="{{ old('interventions_count', $lesson->interventions_count) }}" min="0">
                            @error('interventions_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <label for="critical_mistakes_count" class="form-label">Błędy krytyczne</label>
                            <input type="number" class="form-control text-danger fw-bold @error('critical_mistakes_count') is-invalid @enderror" id="critical_mistakes_count" name="critical_mistakes_count" value="{{ old('critical_mistakes_count', $lesson->critical_mistakes_count) }}" min="0">
                            @error('critical_mistakes_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <label for="minor_mistakes_count" class="form-label">Błędy drobne</label>
                            <input type="number" class="form-control text-warning fw-bold @error('minor_mistakes_count') is-invalid @enderror" id="minor_mistakes_count" name="minor_mistakes_count" value="{{ old('minor_mistakes_count', $lesson->minor_mistakes_count) }}" min="0">
                            @error('minor_mistakes_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="instructor_notes" class="form-label">Notatki z jazdy (opcjonalne)</label>
                        <textarea class="form-control @error('instructor_notes') is-invalid @enderror" id="instructor_notes" name="instructor_notes" rows="3">{{ old('instructor_notes', $lesson->instructor_notes) }}</textarea>
                        @error('instructor_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.lessons.index') }}" class="btn btn-secondary">Anuluj</a>
                    <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
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
