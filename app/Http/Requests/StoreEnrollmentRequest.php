<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Support\Carbon;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'pkk_number' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:in_progress,completed,dropped,cancelled'],
            'enrolled_at' => ['required', 'date'],
            'theory_completed_at' => ['nullable', 'date', 'after_or_equal:enrolled_at'],
            'practice_completed_at' => ['nullable', 'date', 'after_or_equal:theory_completed_at'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                // 1. Sprawdzenie, czy kursant nie jest już zapisany na ten sam kurs
                if ($this->user_id && $this->course_id) {
                    $alreadyEnrolled = Enrollment::where('user_id', $this->user_id)
                        ->where('course_id', $this->course_id)
                        ->exists();

                    if ($alreadyEnrolled) {
                        $validator->errors()->add('user_id', 'Ten kursant jest już zapisany na wybrany kurs.');
                    }
                }

                if ($this->course_id && $this->enrolled_at) {
                    $course = Course::find($this->course_id);

                    if ($course && $course->start_date) {
                        $enrolledAt = Carbon::parse($this->enrolled_at)->startOfDay();
                        $startDate = Carbon::parse($course->start_date)->startOfDay();

                        if ($enrolledAt->gt($startDate)) {
                            $validator->errors()->add(
                                'enrolled_at',
                                "Data zapisu nie może być późniejsza niż data rozpoczęcia kursu ({$startDate->format('d.m.Y')})."
                            );
                        }
                    }
                }
            }
        ];
    }

    public function messages(): array
    {
        return [
            'theory_completed_at.after_or_equal' => 'Data ukończenia teorii nie może być wcześniejsza niż data zapisu na kurs.',
            'practice_completed_at.after_or_equal' => 'Data ukończenia praktyki nie może być wcześniejsza niż data ukończenia teorii.',
        ];
    }
}
