<?php

namespace App\Http\Requests\Student;

use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\InstructorAvailability;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'student';
    }

    public function rules(): array
    {
        return [
            'enrollment_id' => [
                'required',
                'integer',
                Rule::exists('enrollments', 'id')
                    ->where('user_id', Auth::id())
                    ->where('status', 'in_progress'),
            ],
            'instructor_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->where('role', 'instructor')
                    ->whereNull('deleted_at'),
            ],
            'date' => ['required', 'date', 'after_or_equal:tomorrow'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $startTimeObj = Carbon::parse($this->start_time);
                $endTimeObj = Carbon::parse($this->end_time);

                if ((int) abs($startTimeObj->diffInMinutes($endTimeObj)) !== 120) {
                    $validator->errors()->add('start_time', 'Rezerwacja jazdy musi trwać dokładnie 2 godziny.');
                    return;
                }

                $enrollment = Enrollment::with('course')->find($this->enrollment_id);
                $instructor = User::with('assignedVehicle')->find($this->instructor_id);

                if (!$enrollment || !$instructor) {
                    return;
                }

                $hasPassedTheory = Exam::where('enrollment_id', $enrollment->id)
                    ->where('type', 'internal')
                    ->where('category', 'theory')
                    ->where('result', 'passed')
                    ->exists();

                if (!$hasPassedTheory) {
                    $validator->errors()->add('enrollment_id', 'Musisz najpierw zdać wewnętrzny egzamin z teorii, aby umawiać jazdy.');
                    return;
                }

                $vehicle = $instructor->assignedVehicle;
                if (!$vehicle || $vehicle->status !== 'active' || $vehicle->deleted_at !== null) {
                    $validator->errors()->add('instructor_id', 'Wybrany instruktor nie ma aktualnie sprawnego pojazdu.');
                    return;
                }

                if ($enrollment->course->category !== $vehicle->category) {
                    $validator->errors()->add('instructor_id', 'Pojazd wybranego instruktora nie pasuje do kategorii Twojego kursu.');
                    return;
                }

                $startTimeSql = $this->start_time . ':00';
                $endTimeSql = $this->end_time . ':00';

                $isAvailable = InstructorAvailability::where('instructor_id', $instructor->id)
                    ->where('date', $this->date)
                    ->where('start_time', '<=', $startTimeSql)
                    ->where('end_time', '>=', $endTimeSql)
                    ->exists();

                if (!$isAvailable) {
                    $validator->errors()->add('start_time', 'Instruktor nie pracuje w wybranych godzinach.');
                }

                $instructorOverlap = Lesson::where('instructor_id', $instructor->id)
                    ->where('date', $this->date)
                    ->where('status', '!=', 'cancelled')
                    ->where('start_time', '<', $endTimeSql)
                    ->where('end_time', '>', $startTimeSql)
                    ->exists();

                if ($instructorOverlap) {
                    $validator->errors()->add('start_time', 'Wybrany termin u tego instruktora jest już zajęty.');
                }

                $vehicleOverlap = Lesson::where('vehicle_id', $vehicle->id)
                    ->where('date', $this->date)
                    ->where('status', '!=', 'cancelled')
                    ->where('start_time', '<', $endTimeSql)
                    ->where('end_time', '>', $startTimeSql)
                    ->exists();

                if ($vehicleOverlap) {
                    $validator->errors()->add('start_time', 'Pojazd instruktora jest już zajęty w tych godzinach.');
                }

                $studentOverlap = Lesson::where('booked_by_id', Auth::id())
                    ->where('date', $this->date)
                    ->where('status', '!=', 'cancelled')
                    ->where('start_time', '<', $endTimeSql)
                    ->where('end_time', '>', $startTimeSql)
                    ->exists();

                if ($studentOverlap) {
                    $validator->errors()->add('start_time', 'Masz już inną zaplanowaną jazdę w tych godzinach.');
                }
            }
        ];
    }

    public function messages(): array
    {
        return [
            'date.after_or_equal' => 'Jazdę możesz zarezerwować najwcześniej od jutra.',
            'end_time.after' => 'Godzina zakończenia musi być późniejsza niż rozpoczęcia.',
        ];
    }
}
