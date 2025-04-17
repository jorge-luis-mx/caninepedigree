<?php

namespace App\Validations;

use Illuminate\Support\Facades\Validator;

class ValidationsAdminDogs
{
    public static function validate($data)
    {
     
        $rules = [
            'name' => 'required|regex:/^[\pL0-9\s]+$/u',  // Requerido, solo letras y espacios (incluye acentos)
            //'breed' => 'required|regex:/^[\pL0-9\s]+$/u',  // Requerido, letras, números y espacios (incluye acentos)
            'color' => 'required|regex:/^[\pL\s]+$/u',  // Requerido, solo letras y espacios
            'sex' => 'required|in:M,F',  // Requerido, solo letras y espacios

            //'owner_name' => 'required|regex:/^[\pL0-9\s]+$/u',  // Requerido, solo letras y espacios (incluye acentos)
           // 'owner_lastname' => 'required|regex:/^[\pL0-9\s]+$/u',  // Requerido, solo letras y espacios (incluye acentos)
        ];
        
        $messages = [
            'name.required' => 'The name field is required.',
            'name.regex' => 'The name field must only contain letters, spaces, and accents.',
            //'breed.required' => 'The breed field is required.',
            //'breed.regex' => 'The breed field must only contain letters, numbers, spaces, and accents.',
            'color.required' => 'The color field is required.',
            'color.regex' => 'The color field must only contain letters and spaces.',
            'sex.required' => 'The sex field is required.',
            'sex.regex' => 'The sex field must only contain letters and spaces.',

            //'owner_name.required' => 'The name field is required.',
            //'owner_name.regex' => 'The owner_name field must only contain letters, spaces, and accents.',

            //'owner_lastname.required' => 'The name field is required.',
            //'owner_lastname.regex' => 'The owner_lastname field must only contain letters, spaces, and accents.',

        ];
        
        return Validator::make($data, $rules, $messages);

    }
}