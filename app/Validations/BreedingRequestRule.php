<?php

namespace App\Validations;

use Illuminate\Support\Facades\Validator;

class BreedingRequestRule
{
    public static function validate($data)
    {
      
      $rules = [
         'my_dog_id' => 'required|numeric',
         'other_dog_name' => [
             'required',
             'regex:/^[\pL0-9\sáéíóúÁÉÍÓÚñÑ]+$/u'
         ],
         'other_owner_email' => 'required|email',
         'comments' => [
             'nullable',
             'regex:/^[\pL0-9\sáéíóúÁÉÍÓÚñÑ.,!?¡¿()\-\'"]+$/u'
         ],
     ];
     
     $messages = [
         'my_dog_id.required' => 'Please select your dog.',
         'my_dog_id.numeric' => 'Something went wrong selecting your dog. Please try again.',
     
         'other_dog_name.required' => 'Please enter the name of the other dog.',
         'other_dog_name.regex' => 'The dog’s name may only include letters, numbers, and spaces.',
     
         'other_owner_email.required' => 'Please enter the email of the other dog\'s owner.',
         'other_owner_email.email' => 'Please provide a valid email address.',
     
         'comments.regex' => 'Comments can only include letters, numbers, spaces, and basic punctuation.',
     ];
     
     return Validator::make($data, $rules, $messages);
     

   }
}