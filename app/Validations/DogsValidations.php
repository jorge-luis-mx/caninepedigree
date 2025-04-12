<?php

namespace App\Validations;

use Illuminate\Support\Facades\Validator;

class DogsValidations
{
    public static function validate($data)
    {
      
        $rules = [
            'name' => 'required|regex:/^[\pL0-9\s]+$/u',  // Requerido, solo letras y espacios (incluye acentos)
            'breed' => 'required|regex:/^[\pL0-9\s]+$/u',  // Requerido, letras, números y espacios (incluye acentos)
            'color' => 'required|regex:/^[\pL\s]+$/u',  // Requerido, solo letras y espacios
            'sex' => 'required|regex:/^[\pL\s]+$/u',  // Requerido, solo letras y espacios
            'birthdate' => 'required|date',  // Requerido, debe ser una fecha válida
        
            'sire_id' => 'nullable|integer',  // Puede ser vacío o un número entero
            'sire' => 'required_without_all:sire_email,sire_id|nullable|regex:/^[\pL\s]+$/u',  
            'sire_email' => 'nullable|email',  
            'descriptionSire' => 'nullable|regex:/^[\pL0-9\s]+$/u',  
        
            'dam_id' => 'nullable|integer',  // Puede ser vacío o un número entero
            'dam' => 'required_without_all:dam_email,dam_id|nullable|regex:/^[\pL\s]+$/u',  
            'dam_email' => 'nullable|email',  
            'descriptionDam' => 'nullable|regex:/^[\pL0-9\s]+$/u',  
        ];
        
        $messages = [
            'name.required' => 'The name field is required.',
            'name.regex' => 'The name field must only contain letters, spaces, and accents.',
            'breed.required' => 'The breed field is required.',
            'breed.regex' => 'The breed field must only contain letters, numbers, spaces, and accents.',
            'color.required' => 'The color field is required.',
            'color.regex' => 'The color field must only contain letters and spaces.',
            'sex.required' => 'The sex field is required.',
            'sex.regex' => 'The sex field must only contain letters and spaces.',
            'birthdate.required' => 'The birthdate field is required.',
            'birthdate.date' => 'The birthdate must be a valid date.',
        
            'sire_id.integer' => 'The sire ID must be a valid number.',
            'sire.required_without_all' => 'The sire field is required when sire_email and sire_id are not provided.',
            'sire.regex' => 'The sire field must only contain letters and spaces.',
            'sire_email.email' => 'The sire_email must be a valid email address.',
            'descriptionSire.regex' => 'The descriptionSire field must only contain text, numbers, and spaces.',
        
            'dam_id.integer' => 'The dam ID must be a valid number.',
            'dam.required_without_all' => 'The dam field is required when dam_email and dam_id are not provided.',
            'dam.regex' => 'The dam field must only contain letters and spaces.',
            'dam_email.email' => 'The dam_email must be a valid email address.',
            'descriptionDam.regex' => 'The descriptionDam field must only contain text, numbers, and spaces.',
        ];
        
        return Validator::make($data, $rules, $messages);

    }
}