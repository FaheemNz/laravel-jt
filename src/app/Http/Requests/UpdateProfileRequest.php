<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'first_name'  => 'required|string|max:100',
            'last_name'   => 'required|string|max:100',
            'currency_id' => 'required|integer|exists:currencies,id',
            'phone_no'    => 'required|string|max:100',
            'email'       => 'nullable|email:filter|max:100'
        ];
        
        if(request()->input('password')){
            $rules['password'] = 'required|min:8|confirmed|max:100';    
        }  
        
        return $rules;
    }
    
    public function messages()
    {
        return [
            'first_name.string'     =>  'First name is not in correct format',
            'first_name.max'        =>  'First name is too long (Max 100 Characters)',
            'last_name.string'      =>  'Last name is not in correct format',
            'last_name.max'         =>  'Last name is too long (Max 100 characters)',
            'first_name.max'        =>  'First name is too long',
            'last_name.max'         =>  'Lastt name is too long',
            'password.confirmed'    =>  'Passwords dont match',
            'password.min'          =>  'Password should be atleast 8 characters',
            'password.max'          =>  'Password value is too long',
            'currency_id.exists'    =>  'Currency ID does not exist in the system',
            'currency_id.integer'   =>  'Currency ID must be integer',
            'phone_no.max'          =>  'Phone number is too long',
            'email.email'           =>  'Please provide a valid email',
            'email.max'             =>  'Email is too long'
        ];
    }
    
    /**
     * Customize Form Request Errors for a consistent API response
     */
    protected function failedValidation(Validator $validator)
    {
        if($this->expectsJson()){
            $errors = (new ValidationException($validator))->errors();
            
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'data'    => $errors,
                    'message' => 'Profile Update Error'
                ], 422)
            );
        }
        
        parent::failedValidation($validator);
    }
}
