<?php

namespace App\Http\Requests;

use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_additional_hour' => $this->has('is_additional_hour'),
        ]);
    }

    public function rules(): array
    {
        return [
            'enrollment_id' => ['required', 'integer', 'exists:enrollments,id'],
            'instructor_id' => ['required', 'integer', 'exists:users,id'],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'status' => ['required', 'in:scheduled,completed,no_show,cancelled'],
            'is_additional_hour' => ['boolean'],
        ];
    }

   public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->start_time && $this->end_time && $this->date && $this->instructor_id) {
                    $instructorOverlap = Lesson::where('instructor_id', $this->instructor_id)
                        ->where('date', $this->date)
                        ->where('start_time', '<', $this->end_time)
                        ->where('end_time', '>', $this->start_time)
                        ->exists();

                    if ($instructorOverlap) {
                        $validator->errors()->add('start_time', 'Ten instruktor ma już zaplanowaną jazdę w tych godzinach.');
                    }
                }

                if ($this->start_time && $this->end_time && $this->date && $this->vehicle_id) {
                    $vehicleOverlap = Lesson::where('vehicle_id', $this->vehicle_id)
                        ->where('date', $this->date)
                        ->where('start_time', '<', $this->end_time)
                        ->where('end_time', '>', $this->start_time)
                        ->exists();

                    if ($vehicleOverlap) {
                        $validator->errors()->add('vehicle_id', 'Ten pojazd jest już zarezerwowany na inną jazdę w tych godzinach.');
                    }
                }

                if ($this->enrollment_id && $this->vehicle_id) {
                    $enrollment = Enrollment::with('course')->find($this->enrollment_id);
                    $vehicle = Vehicle::find($this->vehicle_id);

                    if ($enrollment && $vehicle && $enrollment->course->category !== $vehicle->category) {
                        $validator->errors()->add('vehicle_id', "Kategoria pojazdu (Kat. {$vehicle->category}) nie pasuje do kategorii kursu (Kat. {$enrollment->course->category}).");
                    }
                }
            }
        ];
    }

    public function messages(): array
    {
        return [
            'end_time.after' => 'Czas zakończenia jazdy musi być późniejszy niż czas rozpoczęcia.',
        ];
    }
}
