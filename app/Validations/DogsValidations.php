<?php

namespace App\Validations;

use Illuminate\Support\Facades\Validator;

class DogsValidations
{
    public static function validate($data)
    {
      
        $rules = [
            'name' => "required|regex:/^[\pL0-9\s'\\(\\)]+$/u", // Requerido, solo letras, números, espacios y paréntesis
            'color' => "required|regex:/^[\pL\s\\-]+$/u", // Requerido, solo letras, espacios y guiones
            'sex' => "required|regex:/^[\pL\s]+$/u", // Requerido, solo letras y espacios
            'birthdate' => 'required|date', // Fecha válida

            // ----------------------
            // SIRE (PADRE)
            // ----------------------
            'sire_id' => 'nullable|integer',

            // Si sire_id está vacío → sire_email obligatorio
            // Si sire_id NO está vacío → sire_email NO se requiere
            'sire_email' => 'required_without:sire_id|nullable|email',

            // Si sire_id está vacío → sire_name obligatorio
            'sire_name' => 'required_without:sire_id|nullable|regex:/^[\pL0-9\s\'&\-\.\(\)]+$/u',

            // Si sire_id existe → sire obligatorio
            // Si sire_id está vacío → sire opcional
            'sire' => 'required_with:sire_id|nullable|regex:/^[\pL0-9\s\'&\-\.\(\)]+$/u',
            'descriptionSire' => "nullable|regex:/^[\pL0-9\s'\\(\\)\\-\\.]+$/u",

            // ----------------------
            // DAM (MADRE)
            // ----------------------
            'dam_id' => 'nullable|integer',

            'dam_email' => 'required_without:dam_id|nullable|email',

            'dam_name' => 'required_without:dam_id|nullable|regex:/^[\pL0-9\s\'&\-\.\(\)]+$/u',

            'dam' => 'required_with:dam_id|nullable|regex:/^[\pL0-9\s\'&\-\.\(\)]+$/u',
            'descriptionDam' => "nullable|regex:/^[\pL0-9\s'\\(\\)\\-\\.]+$/u",
        ];

        $messages = [
            'name.required' => 'The name field is required.',
            'name.regex' => 'The name field must only contain letters, numbers, spaces, and parentheses.',

            'breed.required' => 'The breed field is required.',
            'breed.regex' => 'The breed field must only contain letters, numbers, spaces, and parentheses.',

            'color.required' => 'The color field is required.',
            'color.regex' => 'The color field must only contain letters, spaces, and hyphens.',

            'sex.required' => 'The sex field is required.',
            'sex.regex' => 'The sex field must only contain letters and spaces.',

            'birthdate.required' => 'The birthdate field is required.',
            'birthdate.date' => 'The birthdate must be a valid date.',

            // ---------- SIRE ----------
            'sire_id.integer' => 'The sire ID must be a valid number.',

            'sire_email.required_without' => 'The sire email is required when no sire ID is provided.',
            'sire_email.email' => 'The sire email must be a valid email address.',

            'sire_name.required_without' => 'The sire name is required when no sire ID is provided.',
            'sire_name.regex' => 'The sire name may only contain letters, numbers, spaces, and allowed symbols.',

            'sire.required_with' => 'The sire field is required when a sire ID is provided.',
            'sire.regex' => 'The sire field may only contain letters, numbers, spaces, and allowed symbols.',

            'descriptionSire.regex' => 'The sire description may only contain letters, numbers, spaces, and allowed symbols.',


            // ---------- DAM ----------
            'dam_id.integer' => 'The dam ID must be a valid number.',

            'dam_email.required_without' => 'The dam email is required when no dam ID is provided.',
            'dam_email.email' => 'The dam email must be a valid email address.',

            'dam_name.required_without' => 'The dam name is required when no dam ID is provided.',
            'dam_name.regex' => 'The dam name may only contain letters, numbers, spaces, and allowed symbols.',

            'dam.required_with' => 'The dam field is required when a dam ID is provided.',
            'dam.regex' => 'The dam field may only contain letters, numbers, spaces, and allowed symbols.',

            'descriptionDam.regex' => 'The descriptionDam field must only contain text, numbers, and allowed symbols.',
            // 'sire_id.integer' => 'The sire ID must be a valid number.',
            // 'sire.required_without_all' => 'The sire field is required when sire_email and sire_id are not provided.',
            // 'sire.regex' => 'The sire field must only contain letters, numbers, spaces, and allowed symbols.',
            // 'sire_email.email' => 'The sire_email must be a valid email address.',
            // 'descriptionSire.regex' => 'The descriptionSire field must only contain text, numbers, and allowed symbols.',

            // 'dam_id.integer' => 'The dam ID must be a valid number.',
            // 'dam.required_without_all' => 'The dam field is required when dam_email and dam_id are not provided.',
            // 'dam.regex' => 'The dam field must only contain letters, numbers, spaces, and allowed symbols.',
            // 'dam_email.email' => 'The dam_email must be a valid email address.',
            // 'descriptionDam.regex' => 'The descriptionDam field must only contain text, numbers, and allowed symbols.',
        ];

        
        return Validator::make($data, $rules, $messages);

    }
}