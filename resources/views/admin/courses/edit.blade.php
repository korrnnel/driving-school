<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Edytuj kurs: {{ $course->name }}</h2>
    </x-slot>

    <div class="card shadow-sm max-w-2xl mx-auto">
        <div class="card-body">
            <form action="{{ route('admin.courses.update', $course) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="category" class="form-label">Kategoria <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="A" {{ old('category', $course->category) == 'A' ? 'selected' : '' }}>Kategoria A (Motocykl)</option>
                            <option value="B" {{ old('category', $course->category) == 'B' ? 'selected' : '' }}>Kategoria B (Samochód osobowy)</option>
                            <option value="C" {{ old('category', $course->category) == 'C' ? 'selected' : '' }}>Kategoria C (Ciężarówka)</option>
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <label for="type" class="form-label">Typ kursu <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="standard" {{ old('type', $course->type) == 'standard' ? 'selected' : '' }}>Standardowy</option>
                            <option value="accelerated" {{ old('type', $course->type) == 'accelerated' ? 'selected' : '' }}>Przyspieszony</option>
                            <option value="weekend" {{ old('type', $course->type) == 'weekend' ? 'selected' : '' }}>Weekendowy</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nazwa kursu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $course->name) }}" required maxlength="255">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Data rozpoczęcia</label>
                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $course->start_date) }}">
                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="required_theory_hours" class="form-label">Wymagane godziny teorii <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('required_theory_hours') is-invalid @enderror" id="required_theory_hours" name="required_theory_hours" value="{{ old('required_theory_hours', $course->required_theory_hours) }}" required max="100">
                        @error('required_theory_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <label for="required_practice_hours" class="form-label">Wymagane godziny praktyki <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('required_practice_hours') is-invalid @enderror" id="required_practice_hours" name="required_practice_hours" value="{{ old('required_practice_hours', $course->required_practice_hours) }}" required max="100">
                        @error('required_practice_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Anuluj</a>
                    <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category');
            const theoryInput = document.getElementById('required_theory_hours');
            const practiceInput = document.getElementById('required_practice_hours');

            function updateMinHours() {
                const category = categorySelect.value;
                let minTheory = 30;
                let minPractice = 30;

                if (category === 'A') {
                    minPractice = 20;
                } else if (category === 'C') {
                    minTheory = 20;
                }

                theoryInput.min = minTheory;
                practiceInput.min = minPractice;

                if (theoryInput.value && parseInt(theoryInput.value) < minTheory) {
                    theoryInput.value = minTheory;
                }
                if (practiceInput.value && parseInt(practiceInput.value) < minPractice) {
                    practiceInput.value = minPractice;
                }
            }

            categorySelect.addEventListener('change', updateMinHours);
            updateMinHours();
        });
    </script>
</x-app-layout>
