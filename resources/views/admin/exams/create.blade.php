<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Dodaj nowy egzamin</h2>
    </x-slot>

    <div class="card shadow-sm max-w-2xl mx-auto">
        <div class="card-body">
            <form action="{{ route('admin.exams.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="enrollment_id" class="form-label">Kursant (Zapis) <span class="text-danger">*</span></label>
                    <select class="form-select @error('enrollment_id') is-invalid @enderror" id="enrollment_id" name="enrollment_id" required>
                        <option value="">Wybierz zapis kursanta...</option>
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
                        <label for="type" class="form-label">Typ egzaminu <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="internal" {{ old('type') == 'internal' ? 'selected' : '' }}>Wewnętrzny (Szkoła)</option>
                            <option value="state" {{ old('type') == 'state' ? 'selected' : '' }}>Państwowy (WORD)</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <label for="category" class="form-label">Kategoria egzaminu <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="theory" {{ old('category') == 'theory' ? 'selected' : '' }}>Teoria</option>
                            <option value="practice" {{ old('category') == 'practice' ? 'selected' : '' }}>Praktyka</option>
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="date" class="form-label">Data egzaminu <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                        @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <label for="result" class="form-label">Wynik <span class="text-danger">*</span></label>
                        <select class="form-select @error('result') is-invalid @enderror" id="result" name="result" required>
                            <option value="passed" {{ old('result') == 'passed' ? 'selected' : '' }}>Pozytywny</option>
                            <option value="failed" {{ old('result') == 'failed' ? 'selected' : '' }}>Negatywny</option>
                        </select>
                        @error('result') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-4" id="instructor-container" style="display: none;">
                    <label for="instructor_id" class="form-label">Egzaminator (Instruktor) <span class="text-danger">*</span></label>
                    <select class="form-select @error('instructor_id') is-invalid @enderror" id="instructor_id" name="instructor_id">
                        <option value="">Wybierz instruktora przeprowadzającego egzamin...</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                {{ $instructor->surname }} {{ $instructor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('instructor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">Anuluj</a>
                    <button type="submit" class="btn btn-primary">Zapisz egzamin</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const categorySelect = document.getElementById('category');
            const instructorContainer = document.getElementById('instructor-container');
            const instructorSelect = document.getElementById('instructor_id');

            function toggleInstructor() {
                if (typeSelect.value === 'internal' && categorySelect.value === 'practice') {
                    instructorContainer.style.display = 'block';
                    instructorSelect.setAttribute('required', 'required');
                } else {
                    instructorContainer.style.display = 'none';
                    instructorSelect.removeAttribute('required');
                    instructorSelect.value = '';
                }
            }

            typeSelect.addEventListener('change', toggleInstructor);
            categorySelect.addEventListener('change', toggleInstructor);
            toggleInstructor();
        });
    </script>
</x-app-layout>
