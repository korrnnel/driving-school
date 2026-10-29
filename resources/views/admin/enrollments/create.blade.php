<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Dodaj nowy zapis na kurs</h2>
    </x-slot>

    <div class="card shadow-sm max-w-2xl mx-auto">
        <div class="card-body">
            <form action="{{ route('admin.enrollments.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="user_id" class="form-label">Kursant <span class="text-danger">*</span></label>
                        <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                            <option value="">Wybierz kursanta...</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->surname }} {{ $student->name }} ({{ $student->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <label for="course_id" class="form-label">Kurs <span class="text-danger">*</span></label>
                        <select class="form-select @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                            <option value="">Wybierz kurs...</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>W trakcie</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Ukończony</option>
                            <option value="dropped" {{ old('status') == 'dropped' ? 'selected' : '' }}>Przerwany</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Anulowany</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">
                        <label for="pkk_number" class="form-label">Numer PKK</label>
                        <input type="text" class="form-control @error('pkk_number') is-invalid @enderror" id="pkk_number" name="pkk_number" value="{{ old('pkk_number') }}" maxlength="20">
                        @error('pkk_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="enrolled_at" class="form-label">Data zapisu <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('enrolled_at') is-invalid @enderror" id="enrolled_at" name="enrolled_at" value="{{ old('enrolled_at', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                        @error('enrolled_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mt-3 mt-md-0">
                        <label for="theory_completed_at" class="form-label">Zakończenie teorii</label>
                        <input type="date" class="form-control @error('theory_completed_at') is-invalid @enderror" id="theory_completed_at" name="theory_completed_at" value="{{ old('theory_completed_at') }}">
                        @error('theory_completed_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mt-3 mt-md-0">
                        <label for="practice_completed_at" class="form-label">Zakończenie praktyki</label>
                        <input type="date" class="form-control @error('practice_completed_at') is-invalid @enderror" id="practice_completed_at" name="practice_completed_at" value="{{ old('practice_completed_at') }}">
                        @error('practice_completed_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.enrollments.index') }}" class="btn btn-secondary">Anuluj</a>
                    <button type="submit" class="btn btn-primary">Zapisz przypisanie</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
