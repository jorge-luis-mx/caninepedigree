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
            'companyName' => ['required', 'string', 'max:255','regex:/^[a-zA-Z\s&]+$/'],
            'contactName'=>['required', 'string', 'max:255','regex:/^[a-zA-Z\s&]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 
          
                Rule::unique('providers', 'pvr_email')->ignore(
                    $this->user()->provider->pvr_id, // Ignora el proveedor autenticado
                    'pvr_id' // Columna que actúa como identificador
                ),
            ],
            'phone' => [
                'required', 
                'string',
                'regex:/^\+?[0-9]{7,15}$/', // Acepta números de teléfono internacionales y locales (7-15 dígitos)
            ],
            'country' => [
                'required', 
                'string',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'companyName.required' => 'The provider name is required.',
            'companyName.string' => 'The provider name must be a string.',
            'companyName.max' => 'The provider name must not exceed 255 characters.',
            'companyName.regex' => 'The provider name can only contain letters and spaces.',

            'contactName.required' => 'The provider name is required.',
            'contactName.string' => 'The provider name must be a string.',
            'contactName.max' => 'The provider name must not exceed 255 characters.',
            'contactName.regex' => 'The provider name can only contain letters and spaces.',

            'email.required' => 'The provider email is required.',
            'email.string' => 'The provider email must be a string.',
            'email.lowercase' => 'The provider email must be in lowercase.',
            'email.email' => 'A valid email address must be provided.',
            'email.max' => 'The email address must not exceed 255 characters.',
            'email.unique' => 'The email address is already registered for another provider.',

            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'The phone number must be valid (7-15 digits, optional +).',

            'country.required' => 'The country is required.',
            'country.regex' => 'The country name must only contain letters, numbers, and spaces.',
        ];
    }
}
