<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Provider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255','regex:/^[\pL\pN\s\-\.]+$/u'],
            'kennel_name' => ['required', 'string', 'max:255','regex:/^[\pL\pN\s\-\.]+$/u'],
            
            'last_name' => ['required', 'string', 'max:255','regex:/^[\pL\pN\s\-\.]+$/u'],
            'middle_name' => ['required', 'string', 'max:255','regex:/^[\pL\pN\s\-\.]+$/u'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 
                Rule::unique('user_profiles', 'email')->ignore(
                    $this->user()->UserProfile->profile_id, // Ignora el proveedor autenticado
                    'profile_id' // Columna que actúa como identificador
                ),
            ],
            'phone' => [
                'required', 
                'string',
                'regex:/^\+?[0-9]{7,15}$/', // Acepta números de teléfono internacionales y locales (7-15 dígitos)
            ],

        ];
    }

    public function messages(): array
    {
        return [
            'kennel_name.required' => 'The provider name is required.',
            'kennel_name.string' => 'The provider name must be a string.',
            'kennel_name.max' => 'The provider name must not exceed 255 characters.',
            'kennel_name.regex' => 'El nombre de la empresa solo puede contener letras, espacios y "&". No se permiten números ni caracteres especiales.',

            'fullname.required' => 'The provider name is required.',
            'fullname.string' => 'The provider name must be a string.',
            'fullname.max' => 'The provider name must not exceed 255 characters.',
            'fullname.regex' => 'El nombre de la empresa solo puede contener letras, espacios y "&". No se permiten números ni caracteres especiales.',


            'email.required' => 'The provider email is required.',
            'email.string' => 'The provider email must be a string.',
            'email.lowercase' => 'The provider email must be in lowercase.',
            'email.email' => 'A valid email address must be provided.',
            'email.max' => 'The email address must not exceed 255 characters.',
            'email.unique' => 'The email address is already registered for another provider.',

            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'The phone number must be valid (7-15 digits, optional +).',

            'address.required' => 'The provider name is required.',
            'address.string' => 'The provider name must be a string.',
            'address.max' => 'The provider name must not exceed 255 characters.',
            'address.regex' => 'La dirección solo puede contener letras, números, espacios, comas, puntos, guiones y "#".',

            'country.required' => 'The country is required.',
            'country.regex' => 'The country name must only contain letters, numbers, and spaces.',
        ];
    }
}
