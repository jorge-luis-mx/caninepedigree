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
            'dog_email' => 'required_if:dog_id,null|nullable|email',
            'dogDetails' => 'required_if:dog_id,null|nullable|string',
            'comments' => 'nullable|regex:/^[\pL0-9\sáéíóúÁÉÍÓÚñÑ.,!?¡¿()\-\'"]+$/u',
        ];
    
        $messages = [
            'my_dog_id.required' => 'Please select your dog.',
            'my_dog_id.numeric' => 'Something went wrong selecting your dog. Please try again.',
    
            'dog_id.regex' => 'The dog’s ID may only include letters, numbers, and spaces.',
    
            'dog_email.required_if' => 'Please enter the email of the other dog\'s owner if no dog is selected.',
            'dog_email.email' => 'Please provide a valid email address.',
    
            'dogDetails.required_if' => 'Please enter the details of the other dog if no dog is selected.',
    
            'comments.regex' => 'Comments can only include letters, numbers, spaces, and basic punctuation.',
        ];
    
        return Validator::make($data, $rules, $messages);
     

   }
}