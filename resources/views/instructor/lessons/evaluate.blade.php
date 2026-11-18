<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Karta przebiegu jazdy</h2>
    </x-slot>

    <div class="card shadow-sm max-w-2xl mx-auto mb-4">
        <div class="card-body bg-light">
            <h5 class="mb-1">{{ $lesson->enrollment->user->name }} {{ $lesson->enrollment->user->surname }}</h5>
            <div class="text-muted small">
                Data: {{ \Carbon\Carbon::parse($lesson->date)->format('d.m.Y') }} |
                Czas: {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($lesson->end_time)->format('H:i') }}
            </div>
        </div>
    </div>

    <div class="card shadow-sm max-w-2xl mx-auto">
        <div class="card-body">
            <form action="{{ route('instructor.lessons.updateEvaluation', $lesson) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="status" class="form-label">Zmień status jazdy <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="completed" {{ old('status', $lesson->status) == 'completed' ? 'selected' : '' }}>Zakończona pomyślnie</option>
                        <option value="no_show" {{ old('status', $lesson->status) == 'no_show' ? 'selected' : '' }}>Nieobecność kursanta (No-show)</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div id="evaluation-fields">
                    <div class="mb-4">
                        <label class="form-label text-primary fw-bold">Zadania realizowane podczas jazdy</label>
                        <div class="row">
                            <div class="col-6">
                                <label class="small text-muted mb-1">Zaplanowane</label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" type="button" onclick="updateValue('tasks_planned_count', -1)">-</button>
                                    <input type="number" class="form-control text-center @error('tasks_planned_count') is-invalid @enderror" id="tasks_planned_count" name="tasks_planned_count" value="{{ old('tasks_planned_count', $lesson->tasks_planned_count) }}" min="0" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="updateValue('tasks_planned_count', 1)">+</button>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted mb-1">Zrealizowane</label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" type="button" onclick="updateValue('tasks_completed_count', -1)">-</button>
                                    <input type="number" class="form-control text-center @error('tasks_completed_count') is-invalid @enderror" id="tasks_completed_count" name="tasks_completed_count" value="{{ old('tasks_completed_count', $lesson->tasks_completed_count) }}" min="0" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="updateValue('tasks_completed_count', 1)">+</button>
                                </div>
                                @error('tasks_completed_count') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-danger fw-bold">Błędy popełnione przez kursanta</label>

                        <div class="mb-3">
                            <label class="small text-muted mb-1">Drobne potknięcia</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button" onclick="updateValue('minor_mistakes_count', -1)">-</button>
                                <input type="number" class="form-control text-center @error('minor_mistakes_count') is-invalid @enderror" id="minor_mistakes_count" name="minor_mistakes_count" value="{{ old('minor_mistakes_count', $lesson->minor_mistakes_count) }}" min="0" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="updateValue('minor_mistakes_count', 1)">+</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small text-muted mb-1">Błędy krytyczne (Zagrożenie)</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button" onclick="updateValue('critical_mistakes_count', -1)">-</button>
                                <input type="number" class="form-control text-center text-danger fw-bold @error('critical_mistakes_count') is-invalid @enderror" id="critical_mistakes_count" name="critical_mistakes_count" value="{{ old('critical_mistakes_count', $lesson->critical_mistakes_count) }}" min="0" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="updateValue('critical_mistakes_count', 1)">+</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small text-muted mb-1">Fizyczne interwencje instruktora</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button" onclick="updateValue('interventions_count', -1)">-</button>
                                <input type="number" class="form-control text-center text-danger fw-bold @error('interventions_count') is-invalid @enderror" id="interventions_count" name="interventions_count" value="{{ old('interventions_count', $lesson->interventions_count) }}" min="0" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="updateValue('interventions_count', 1)">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="instructor_notes" class="form-label fw-bold">Komentarz i zalecenia (opcjonalnie)</label>
                        <textarea class="form-control @error('instructor_notes') is-invalid @enderror" id="instructor_notes" name="instructor_notes" rows="3">{{ old('instructor_notes', $lesson->instructor_notes) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('instructor.lessons.index') }}" class="btn btn-secondary">Anuluj</a>
                    <button type="submit" class="btn btn-success px-4">Zapisz kartę jazdy</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateValue(fieldId, change) {
            const input = document.getElementById(fieldId);
            let currentValue = parseInt(input.value) || 0;
            let newValue = currentValue + change;

            if (newValue >= 0) {
                input.value = newValue;
            }

            if (fieldId === 'tasks_planned_count') {
                const completedInput = document.getElementById('tasks_completed_count');
                if (parseInt(completedInput.value) > newValue) {
                    completedInput.value = newValue;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const evaluationFields = document.getElementById('evaluation-fields');

            function toggleFields() {
                if (statusSelect.value === 'no_show') {
                    evaluationFields.style.display = 'none';
                } else {
                    evaluationFields.style.display = 'block';
                }
            }

            statusSelect.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
</x-app-layout>
