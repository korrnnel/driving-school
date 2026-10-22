<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category' => ['required', 'in:A,B,C'],
            'brand' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'registration_number' => [
                'required',
                'string',
                'max:10',
                Rule::unique('vehicles')->ignore($this->route('vehicle'))
            ],
            'status' => ['required', 'in:active,maintenance,out_of_service'],
        ];
    }

    public function messages(): array
    {
        return [
            'registration_number.unique' => 'Ten numer rejestracyjny jest już przypisany do innego pojazdu.',
        ];
    }
}
