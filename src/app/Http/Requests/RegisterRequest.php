<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
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
        return [
            'first_name'    =>    'required|string|max:100',
            'last_name'     =>    'required|string|max:100',
            'email'         =>    'required|string|email:filter|unique:users,email|max:50',
            'currency_id'   =>    'required|exists:currencies,id',
            'password'      =>    'required|string|min:8|max:100',
            'phone_no'      =>    'required|string|min:6|max:20|unique:users,phone_no',
            'image_id'      =>    'nullable|integer|exists:images,id'
        ];
    }
    
    public function messages()
    {
        return [
            'first_name.string'     =>      'First Name contains Invalid Characters',
            'first_name.max'        =>      'First Name length is too long',
            'last_name.string'      =>      'Last Name contains Invalid Characters',
            'last_name.max'         =>      'Last Name length is too long',
            'email.max'             =>      'Email length is too long',
            'email.unique'          =>      'The email you provided already exists. Please use another email',
            'email.email'           =>      'Email is not in correct format',
            'currency_id.exists'    =>      'Currency ID does not exist in the system',
            'phone_no.max'          =>      'Phone Length is too long',
            'phone_no.min'          =>      'Phone number is too short',
            'password.min'          =>      'Password value is too short',
            'password.max'          =>      'Password value is too long',
            'image_id.integer'      =>      'Invalid Image ID provided',
            'image_id.exists'       =>      'The uploaded image doesnt exist',
            'phone_no.unique'       =>      'Please try a different phone number. This phone number already exists'
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
                    'message' => 'Invalid data provided in the registeration'
                ], 422)
            );
        }
        
        parent::failedValidation($validator);
    }
}
