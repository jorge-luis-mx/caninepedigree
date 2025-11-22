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
            'last_name' => ['required', 'string', 'max:255','regex:/^[\pL\pN\s\-\.]+$/u'],
            'middle_name' => ['required', 'string', 'max:255','regex:/^[\pL\pN\s\-\.]+$/u'],
            
            // Checkbox es opcional (nullable o boolean)
            'use_kennel_name' => ['nullable', 'boolean'],
            
            // kennel_name es requerido SOLO si use_kennel_name está checkeado
            'kennel_name' => [
                'required_if:use_kennel_name,1',
                'nullable',
                'string',
                'max:255',
                'regex:/^[\pL\pN\s\-\.]+$/u'
            ],
            
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 
                Rule::unique('user_profiles', 'email')->ignore(
                    $this->user()->UserProfile->profile_id,
                    'profile_id'
                ),
            ],
            'phone' => [
                'required', 
                'string',
                'regex:/^\+?[0-9]{7,15}$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kennel_name.required_if' => 'The kennel name is required when "Accept Kennel Name" is checked.',
            'kennel_name.string' => 'The kennel name must be a string.',
            'kennel_name.max' => 'The kennel name must not exceed 255 characters.',
            'kennel_name.regex' => 'El nombre del kennel solo puede contener letras, números, espacios, guiones y puntos.',

            'first_name.required' => 'The first name is required.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'The first name must not exceed 255 characters.',
            'first_name.regex' => 'El nombre solo puede contener letras, espacios, guiones y puntos.',

            'last_name.required' => 'The last name is required.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name must not exceed 255 characters.',
            'last_name.regex' => 'El apellido solo puede contener letras, espacios, guiones y puntos.',

            'middle_name.required' => 'The middle name is required.',
            'middle_name.string' => 'The middle name must be a string.',
            'middle_name.max' => 'The middle name must not exceed 255 characters.',
            'middle_name.regex' => 'El segundo nombre solo puede contener letras, espacios, guiones y puntos.',

            'email.required' => 'The email is required.',
            'email.string' => 'The email must be a string.',
            'email.lowercase' => 'The email must be in lowercase.',
            'email.email' => 'A valid email address must be provided.',
            'email.max' => 'The email address must not exceed 255 characters.',
            'email.unique' => 'The email address is already registered.',

            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'The phone number must be valid (7-15 digits, optional +).',
        ];
    }
}
