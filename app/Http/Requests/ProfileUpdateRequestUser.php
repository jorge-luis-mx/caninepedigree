<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Provider;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequestUser extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'userName' => ['required', 'string', 'max:255','regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 
          
                Rule::unique('providers', 'pvr_email')->ignore(
                    $this->user()->provider->pvr_id, // Ignora el proveedor autenticado
                    'pvr_id' // Columna que actúa como identificador
                ),
            ],

        ];
    }

    public function messages(): array
    {
        return [
            'userName.required' => 'The username is required.',
            'userName.string' => 'The username must be a string.',
            'userName.max' => 'The username must not exceed 255 characters.',
            'userName.regex' => 'The username can only contain letters and spaces.',

            'email.required' => 'The email is required.',
            'email.string' => 'The email must be a string.',
            'email.lowercase' => 'The email must be in lowercase.',
            'email.email' => 'A valid email address must be entered.',
            'email.max' => 'The email address must not exceed 255 characters.',
            'email.unique' => 'The email address is already registered for another user.',
        ];
    }
}
