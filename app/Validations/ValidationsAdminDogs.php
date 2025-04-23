<?php

namespace App\Validations;

use Illuminate\Support\Facades\Validator;

class ValidationsAdminDogs
{
    public static function validate($data)
    {
     
        $rules = [
            'name' => 'required|regex:/^[\pL0-9\s\'\(\)]+$/u',  // Letras, números, espacios, paréntesis y apóstrofes
            'color' => 'required|regex:/^[\pL\s\-]+$/u',        // Letras, espacios y guiones
            'sex' => 'required|in:M,F',                         // Solo acepta "M" o "F"
        ];
        
        $messages = [
            'name.required' => 'The name field is required.',
            'name.regex' => 'The name field may only contain letters, numbers, spaces, apostrophes, and parentheses.',
            
            'color.required' => 'The color field is required.',
            'color.regex' => 'The color field may only contain letters, spaces, and hyphens.',
            
            'sex.required' => 'The sex field is required.',
            'sex.in' => 'The sex field must be either M (Male) or F (Female).',
        ];       
        
        return Validator::make($data, $rules, $messages);

    }
}