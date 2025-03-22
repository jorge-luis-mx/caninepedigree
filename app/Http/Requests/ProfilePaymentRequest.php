<?php

namespace App\Http\Requests;

use App\Models\Billing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;



class ProfilePaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['nullable', 'integer', 'exists:pvr_billing_data,pvr_id'],
            'bank_name' => ['required', 'string', 'max:255','regex:/^[a-zA-Z\s]+$/'],
            'paypal_email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'accounNumber' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9]+$/'],
            'banckCountUs' => ['required', 'in:0,1'],
            'swift' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9]+$/'],
            'routing' => ['required', 'regex:/^\d+$/'], 
            'platform' => ['required', 'regex:/^[0-9]+$/'],
        ];
    }

    public function messages(): array
    {
        return [

            'bank_name.required' => 'The provider name is required.',
            'bank_name.string' => 'The provider name must be a string.',
            'bank_name.max' => 'The provider name must not exceed 255 characters.',
            'bank_name.regex' => 'The provider name can only contain letters and spaces.',

            'paypal_email.required' => 'The provider email is required.',
            'paypal_email.string' => 'The provider email must be a string.',
            'paypal_email.lowercase' => 'The provider email must be in lowercase.',
            'paypal_email' => 'A valid email address must be provided.',
            'paypal_email.max' => 'The email address must not exceed 255 characters.',
            'paypal_email.unique' => 'The email address is already registered for another provider.',

            'accounNumber.required' => 'This field is required.',
            'accounNumber.string' => 'This field must be a string.',
            'accounNumber.max' => 'This field must not exceed 255 characters.',
            'accounNumber.regex' => 'This field can only contain letters, numbers, with no spaces.',

            'swift.required' => 'This field is required.',
            'swift.string' => 'This field must be a string.',
            'swift.max' => 'This field must not exceed 255 characters.',
            'swift.regex' => 'This field can only contain letters, numbers, with no spaces.',

            'banckCountUs.in' => 'El campo banckCountUs solo puede contener los valores 0 o 1.',
            'banckCountUs.required' => 'El campo banckCountUs es obligatorio.',

            'routing.required' => 'El campo routing es obligatorio.',
            'routing.regex' => 'El campo routing debe contener solo números enteros.',


            'platform.required' => 'El campo platform es obligatorio.',
            'platform.regex' => 'El campo platform solo puede contener letras y no debe tener espacios.',
        ];
    }



    
}
