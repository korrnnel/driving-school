<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'surname' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:15', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,instructor,student'],
            'license_number' => ['nullable', 'required_if:role,instructor', 'string', 'max:7', 'unique:users,license_number'],
            'assigned_vehicle_id' => ['nullable', 'exists:vehicles,id', 'unique:users,assigned_vehicle_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Ten adres e-mail jest już zajęty.',
            'phone.unique' => 'Ten numer telefonu jest już przypisany do innego konta.',
            'license_number.unique' => 'Ten numer uprawnień jest już przypisany do innego instruktora.',
            'assigned_vehicle_id.unique' => 'Ten pojazd jest już przypisany do innego instruktora.',
            'license_number.required_if' => 'Numer uprawnień jest wymagany dla instruktora.',
        ];
    }
}
