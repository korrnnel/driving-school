<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Instruktor może oceniać tylko SWOJE jazdy
        $lesson = $this->route('lesson');
        return $lesson && $lesson->instructor_id === Auth::id();
    }

    public function rules(): array
    {
        return [
            'tasks_planned_count' => ['required', 'integer', 'min:0'],
            'tasks_completed_count' => ['required', 'integer', 'min:0', 'lte:tasks_planned_count'],
            'interventions_count' => ['required', 'integer', 'min:0'],
            'critical_mistakes_count' => ['required', 'integer', 'min:0'],
            'minor_mistakes_count' => ['required', 'integer', 'min:0'],
            'instructor_notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:completed,no_show'],
        ];
    }

    public function messages(): array
    {
        return [
            'tasks_completed_count.lte' => 'Liczba zrealizowanych zadań nie może być większa niż zaplanowanych.',
        ];
    }
}
