<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category = $this->input('category');

        $minTheory = match($category) {
            'C' => 20,
            default => 30,
        };

        $minPractice = match($category) {
            'A' => 20,
            default => 30,
        };

        return [
            'category' => ['required', 'in:A,B,C'],
            'type' => ['required', 'in:standard,accelerated,weekend'],
            'name' => ['required', 'string', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'required_theory_hours' => ['required', 'integer', 'min:' . $minTheory, 'max:100'],
            'required_practice_hours' => ['required', 'integer', 'min:' . $minPractice, 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'required_theory_hours.min' => 'Dla wybranej kategorii wymagane jest minimum :min godzin teorii.',
            'required_practice_hours.min' => 'Dla wybranej kategorii wymagane jest minimum :min godzin praktyki.',
        ];
    }
}
