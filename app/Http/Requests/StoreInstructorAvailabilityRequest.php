<?php

namespace App\Http\Requests;

use App\Models\InstructorAvailability;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreInstructorAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'instructor_id' => ['required', 'integer', 'exists:users,id'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->start_time && $this->end_time && $this->date && $this->instructor_id) {
                    $overlap = InstructorAvailability::where('instructor_id', $this->instructor_id)
                        ->where('date', $this->date)
                        ->where('start_time', '<', $this->end_time)
                        ->where('end_time', '>', $this->start_time)
                        ->exists();

                    if ($overlap) {
                        $validator->errors()->add('start_time', 'Ten instruktor ma już wprowadzony inny blok godzinowy nakładający się na ten czas.');
                    }
                }
            }
        ];
    }

    public function messages(): array
    {
        return [
            'end_time.after' => 'Koniec pracy musi być później niż jej rozpoczęcie.',
        ];
    }
}
