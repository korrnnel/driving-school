<?php

namespace App\Http\Requests;

use App\Models\Exam;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'enrollment_id' => ['required', 'integer', 'exists:enrollments,id'],
            'type' => ['required', 'in:internal,state'],
            'category' => ['required', 'in:theory,practice'],
            'result' => ['required', 'in:passed,failed'],
            'date' => ['required', 'date'],
            'instructor_id' => [
                'nullable',
                Rule::requiredIf(fn () => $this->type === 'internal' && $this->category === 'practice'),
                'integer',
                'exists:users,id'
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->enrollment_id && $this->type && $this->category) {
                    $alreadyPassed = Exam::where('enrollment_id', $this->enrollment_id)
                        ->where('type', $this->type)
                        ->where('category', $this->category)
                        ->where('result', 'passed')
                        ->exists();

                    if ($alreadyPassed) {
                        $validator->errors()->add('result', 'Ten kursant ma już w systemie zdany ten egzamin. Nie można dodać kolejnego podejścia.');
                    }
                }
            }
        ];
    }

    public function messages(): array
    {
        return [
            'instructor_id.required' => 'Egzamin wewnętrzny z praktyki musi mieć przypisanego instruktora.',
        ];
    }
}
