<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:15', 'regex:/^[0-9\+\-\s]+$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Konto z tym adresem e-mail już istnieje.',
            'phone.unique' => 'Ten numer telefonu jest już przypisany do innego konta.',
            'phone.regex' => 'Numer telefonu może zawierać tylko cyfry, spacje, myślniki i znak plus.',
            'password.min' => 'Hasło musi mieć co najmniej 8 znaków.',
            'password.confirmed' => 'Hasła muszą być identyczne.',
        ];
    }
}
