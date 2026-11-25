<?php

namespace App\Http\Requests\Student;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'student';
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'integer', Rule::exists('courses', 'id')->whereNull('deleted_at')],
            'pkk_number' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $course = $this->course_id ? Course::find($this->course_id) : null;

                if (!$course) {
                    return;
                }

                if ($course->start_date && Carbon::parse($course->start_date)->startOfDay()->lt(Carbon::today())) {
                    $validator->errors()->add('course_id', 'Ten kurs już się rozpoczął. Zapisy zostały zamknięte.');
                }

                $alreadyEnrolled = Enrollment::where('user_id', Auth::id())
                    ->where('course_id', $course->id)
                    ->exists();

                if ($alreadyEnrolled) {
                    $validator->errors()->add('course_id', 'Wysłałeś już zgłoszenie lub jesteś zapisany na ten kurs.');
                }

                $sameCategoryExists = Enrollment::where('user_id', Auth::id())
                    ->whereIn('status', ['waiting', 'in_progress', 'completed'])
                    ->whereHas('course', function ($query) use ($course) {
                        $query->where('category', $course->category);
                    })
                    ->exists();

                if ($sameCategoryExists) {
                    $validator->errors()->add('course_id', "Masz już oczekujące, aktywne lub ukończone szkolenie kategorii {$course->category}.");
                }
            }
        ];
    }
}
