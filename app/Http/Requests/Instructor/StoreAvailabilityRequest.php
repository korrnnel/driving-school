<?php

namespace App\Http\Requests\Instructor;

use App\Models\InstructorAvailability;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class StoreAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->start_time && $this->end_time && $this->date) {
                    $overlap = InstructorAvailability::where('instructor_id', Auth::id())
                        ->where('date', $this->date)
                        ->where('start_time', '<', $this->end_time)
                        ->where('end_time', '>', $this->start_time)
                        ->exists();

                    if ($overlap) {
                        $validator->errors()->add('start_time', 'Masz już dodany grafik, który pokrywa się z tymi godzinami.');
                    }
                }
            }
        ];
    }

    public function messages(): array
    {
        return [
            'date.after_or_equal' => 'Nie możesz dodawać grafiku z datą wsteczną.',
            'end_time.after' => 'Koniec pracy musi być później niż jej rozpoczęcie.',
        ];
    }
}
