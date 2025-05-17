<?php

namespace App\Validations;

use Illuminate\Support\Facades\Validator;

class PuppiesValidations
{
    public static function validate($data)
    {
        $rules = [
            // Validar campos del padre y madre
            'totalPuppies'=>'required',
            'sire' => 'required|regex:/^[\pL0-9\s\'\(\)]+$/u',
            'sire_id' => 'required|integer',
            'dam' => 'required|regex:/^[\pL0-9\s\'\(\)]+$/u',
            'dam_id' => 'required|integer',

            // Validar el array de cachorros
            'puppies' => 'required|array|min:1',
            'puppies.*.name' => 'required|regex:/^[\pL0-9\s\'\(\)]+$/u',
            'puppies.*.color' => 'required|regex:/^[\pL\s\-]+$/u',
            'puppies.*.sex' => 'required|in:M,F',
            'puppies.*.birthdate' => 'required|date',
        ];

        $messages = [
            // Mensajes para sire y dam
            'sire.required' => 'The sire field is required.',
            'sire.regex' => 'The sire name may only contain letters, numbers, spaces, apostrophes, and parentheses.',
            'sire_id.required' => 'The sire ID is required.',
            'sire_id.integer' => 'The sire ID must be a valid number.',

            'dam.required' => 'The dam field is required.',
            'dam.regex' => 'The dam name may only contain letters, numbers, spaces, apostrophes, and parentheses.',
            'dam_id.required' => 'The dam ID is required.',
            'dam_id.integer' => 'The dam ID must be a valid number.',

            // Mensajes para puppies
            'puppies.required' => 'At least one puppy must be provided.',

            'puppies.*.name.required' => 'Each puppy must have a name.',
            'puppies.*.name.regex' => 'The name may only contain letters, numbers, spaces, apostrophes, and parentheses.',

            'puppies.*.color.required' => 'Each puppy must have a color.',
            'puppies.*.color.regex' => 'The color may only contain letters, spaces, and hyphens.',

            'puppies.*.sex.required' => 'Each puppy must have a sex.',
            'puppies.*.sex.in' => 'The sex must be M (Male) or F (Female).',

            'puppies.*.birthdate.required' => 'Each puppy must have a birthdate.',
            'puppies.*.birthdate.date' => 'The birthdate must be a valid date.',
        ];

        return Validator::make($data, $rules, $messages);
    }
}

