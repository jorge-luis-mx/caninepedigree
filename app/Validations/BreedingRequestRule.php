<?php

namespace App\Validations;

use Illuminate\Support\Facades\Validator;

class BreedingRequestRule
{
    public static function validate($data)
    {
    
        $rules = [
            'my_dog_id' => 'required|numeric',

            'dog_id' => 'nullable|regex:/^[\pL0-9\sáéíóúÁÉÍÓÚñÑ-]+$/u',

            // obligatorio si dog_id está vacío
            'dog_email' => 'required_without:dog_id|nullable|email',

            // obligatorio si dog_id está vacío
            'sire_name' => 'required_without:dog_id|nullable|regex:/^[\pL0-9\sáéíóúÁÉÍÓÚñÑ\'\-\.\(\)\s]+$/u',

            // 🔥 se queda como estaba
            'dogDetails' => 'required_if:dog_id,null|nullable|string',
        ];

    
        $messages = [
            'my_dog_id.required' => 'Please select your dog.',
            'my_dog_id.numeric' => 'Something went wrong selecting your dog. Please try again.',

            'dog_id.regex' => 'The dog’s ID may only include letters, numbers, and spaces.',

            'dog_email.required_without' => 'Please enter the email of the other dog\'s owner if no dog is selected.',
            'dog_email.email' => 'Please provide a valid email address.',

            'sire_name.required_without' => 'Please enter the name of the other dog if no dog is selected.',
            'sire_name.regex' => 'The name may only include letters, numbers, spaces, and allowed symbols.',

            // se queda como estaba
            'dogDetails.required_if' => 'Please enter the details of the other dog if no dog is selected.',
        ];

        return Validator::make($data, $rules, $messages);
     

   }
}