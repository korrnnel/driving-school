<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name' => ['required', 'string', 'max:50'],
            'surname' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'phone' => ['required', 'string', 'max:15', Rule::unique('users')->ignore($userId)],
            // Hasło przy edycji jest opcjonalne (zmieniamy tylko jak ktoś coś wpisze)
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:admin,instructor,student'],
            'license_number' => ['nullable', 'required_if:role,instructor', 'string', 'max:7', Rule::unique('users')->ignore($userId)],
            'assigned_vehicle_id' => ['nullable', 'exists:vehicles,id', Rule::unique('users')->ignore($userId)],
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
