<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Edytuj godziny pracy instruktora</h2>
    </x-slot>

    <div class="card shadow-sm max-w-2xl mx-auto">
        <div class="card-body">
            <form action="{{ route('admin.instructor-availabilities.update', $instructorAvailability) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="instructor_id" class="form-label">Instruktor <span class="text-danger">*</span></label>
                    <select class="form-select @error('instructor_id') is-invalid @enderror" id="instructor_id" name="instructor_id" required>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}" {{ old('instructor_id', $instructorAvailability->instructor_id) == $instructor->id ? 'selected' : '' }}>
                                {{ $instructor->surname }} {{ $instructor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('instructor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="date" class="form-label">Data <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', \Carbon\Carbon::parse($instructorAvailability->date)->format('Y-m-d')) }}" required>
                        @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mt-3 mt-md-0">
                        <label for="start_time" class="form-label">Od godziny <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($instructorAvailability->start_time)->format('H:i')) }}" required>
                        @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mt-3 mt-md-0">
                        <label for="end_time" class="form-label">Do godziny <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($instructorAvailability->end_time)->format('H:i')) }}" required>
                        @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.instructor-availabilities.index') }}" class="btn btn-secondary">Anuluj</a>
                    <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
