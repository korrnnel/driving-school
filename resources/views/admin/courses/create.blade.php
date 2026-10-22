<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Dodaj nowy kurs</h2>
    </x-slot>

    <div class="card shadow-sm max-w-2xl mx-auto">
        <div class="card-body">
            <form action="{{ route('admin.courses.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="category" class="form-label">Kategoria <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="">Wybierz...</option>
                            <option value="A" {{ old('category') == 'A' ? 'selected' : '' }}>Kategoria A (Motocykl)</option>
                            <option value="B" {{ old('category') == 'B' ? 'selected' : '' }}>Kategoria B (Samochód osobowy)</option>
                            <option value="C" {{ old('category') == 'C' ? 'selected' : '' }}>Kategoria C (Ciężarówka)</option>
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <label for="type" class="form-label">Typ kursu <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="standard" {{ old('type') == 'standard' ? 'selected' : '' }}>Standardowy</option>
                            <option value="accelerated" {{ old('type') == 'accelerated' ? 'selected' : '' }}>Przyspieszony</option>
                            <option value="weekend" {{ old('type') == 'weekend' ? 'selected' : '' }}>Weekendowy</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nazwa kursu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required maxlength="255">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Data rozpoczęcia</label>
                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}">
                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Pozostaw puste, jeśli kurs jeszcze nie ma zaplanowanego startu.</div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="required_theory_hours" class="form-label">Wymagane godziny teorii <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('required_theory_hours') is-invalid @enderror" id="required_theory_hours" name="required_theory_hours" value="{{ old('required_theory_hours', 30) }}" required min="0" max="100">
                        @error('required_theory_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <label for="required_practice_hours" class="form-label">Wymagane godziny praktyki <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('required_practice_hours') is-invalid @enderror" id="required_practice_hours" name="required_practice_hours" value="{{ old('required_practice_hours', 30) }}" required min="0" max="100">
                        @error('required_practice_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Anuluj</a>
                    <button type="submit" class="btn btn-primary">Zapisz kurs</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
