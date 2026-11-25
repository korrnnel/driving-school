<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

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
            'pkk_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'status' => ['required', 'in:waiting,in_progress,completed,dropped,cancelled'],
            'enrolled_at' => ['required', 'date'],
            'theory_completed_at' => ['nullable', 'date', 'after_or_equal:enrolled_at'],
            'practice_completed_at' => ['nullable', 'date', 'after_or_equal:theory_completed_at'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $course = $this->course_id ? Course::find($this->course_id) : null;

                if ($this->user_id && $course) {
                    $alreadyEnrolled = Enrollment::where('user_id', $this->user_id)
                        ->where('course_id', $course->id)
                        ->exists();

                    if ($alreadyEnrolled) {
                        $validator->errors()->add('user_id', 'Ten kursant jest już zapisany na wybrany kurs.');
                    }

                    if (in_array($this->status, ['waiting', 'in_progress', 'completed'], true)) {
                        $sameCategoryExists = Enrollment::where('user_id', $this->user_id)
                            ->whereIn('status', ['waiting', 'in_progress', 'completed'])
                            ->whereHas('course', function ($query) use ($course) {
                                $query->where('category', $course->category);
                            })
                            ->exists();

                        if ($sameCategoryExists) {
                            $validator->errors()->add('course_id', "Ten kursant ma już oczekujący, aktywny lub ukończony kurs kategorii {$course->category}.");
                        }
                    }
                }

                if ($course && $course->start_date && $this->enrolled_at) {
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
        ];
    }

    public function messages(): array
    {
        return [
            'theory_completed_at.after_or_equal' => 'Data ukończenia teorii nie może być wcześniejsza niż data zapisu na kurs.',
            'practice_completed_at.after_or_equal' => 'Data ukończenia praktyki nie może być wcześniejsza niż data ukończenia teorii.',
            'pkk_number.regex' => 'Numer PKK może składać się wyłącznie z cyfr.',
        ];
    }
}
