<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class OfferRequest extends FormRequest
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
            'price'         =>      'numeric|required',
            'reward'        =>      'numeric|required',
            'expiry_date'   =>      'date|required|date_format:Y-m-d',
            'order_id'      =>      'required|exists:orders,id',
            'trip_id'       =>      'required|exists:trips,id',
        ];
    }

    public function messages()
    {
        return [
            'description.required'      =>      'Description is required',
            'description.max'           =>      'Description value is too long',
            'description.string'        =>      'Description value is not valid',
            'price.numeric'             =>      'Price value is not valid',
            'price.required'            =>      'Price value is required',
            'reward.numeric'            =>      'Reward value is not valid',
            'reward.required'           =>      'Reward value is required',
            'expiry_date.required'      =>      'Expiry date is required',
            'expiry_date.date'          =>      'Expiry date must be a valid date',
            'expiry_date.date_format'   =>      'Expiry date must be in Y-m-d format',
            'order_id.required'         =>      'Order ID is required',
            'order_id.exists'           =>      'The order you provided does not exist in our system',
            'trip_id.required'          =>      'Trip ID is required',
            'trip_id.exists'            =>      'Trip ID you provided does not exist in our system'
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
                    'message' => 'Invalid Offer data provided'
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
