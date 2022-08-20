<?php

namespace App\Http\Requests;

use App\Rules\UniqueOrderName;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OrderRequest extends FormRequest
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
            'name'                  =>      ['required', 'c_text', 'max:100'],
            'thumbnail'             =>      'nullable|image',
            'description'           =>      'required|string|max:200',
            'category_id'           =>      'required|integer|exists:categories,id',
            'url'                   =>      'nullable|string|max:100',
            'weight'                =>      'required|integer|lt:100',
            'quantity'              =>      'required|numeric|min:1|lt:100',
            'price'                 =>      'required|numeric|gt:0.1',
            'currency_id'           =>      'required|integer|exists:currencies,id',
            'reward'                =>      'required|numeric|gt:0.1',
            'with_box'              =>      'required|boolean',
            'needed_by'             =>      'required|date',
            'from_city_id'          =>      'required|integer|exists:cities,id',
            'destination_city_id'   =>      'required|integer|exists:cities,id|different:from_city_id',
            'images'                =>      'required|array',
            'images.*'              =>      'nullable|image',
            'is_doorstep_delivery'  =>      'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            'orders.unique'                 =>      'Order name not available. Please try a different name',
            'ordrs.c_text'                  =>      'Order name contains invalid characters',
            'description.string'            =>      'Description contains invalid characters',
            'category_id.exists'            =>      'Category ID does not exist in the system',
            'destination_city_id.exists'    =>      'Destination City ID does not exist in the system',
            'from_city_id.exists'           =>      'From City ID does not exist in system',
            'weight.numeric'                =>      'Weight value must be Numeric',
            'weight.lt'                     =>      'Weight value must be less than 100',
            'quantity.numeric'              =>      'Quantity value must be Numeric',
            'quantity.lt'                   =>      'Quantity value must be less than 100',
            'price.numeric'                 =>      'Price value must be numeric',
            'with_box.boolean'              =>      'With_Box value must be boolean',
            'needed_by.date'                =>      'Needed by value must be a valid date',
            'from_city_id.exists'           =>      'From City is not valid',
            'from_city_id.required'         =>      'From City is required',
            'desination_city_id.required'   =>      'Destination City is required',
            'destination_city_id.exists'    =>      'Destination City is not valid',
            'destination_city_id.different' =>      '\'From City\' must be different from \'Destination_City\'',
            'images.required'               =>      'Images are required for order',
            'images.*.image'                =>      'Please upload a valid image',
            'is_doorstep_delivery.boolean'  =>      'Is_Doorstep_Delivery value must be boolean'
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
                    'message' => 'Invalid Order data provided'
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
