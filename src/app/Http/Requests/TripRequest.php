<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripRequest extends FormRequest
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
            'from_city_id'          => 'required|integer|exists:cities,id',
            'destination_city_id'   => 'required|integer|exists:cities,id|different:from_city_id',
            'arrival_date'          => 'date|required|date_format:Y-m-d'
        ];
    }

    public function messages()
    {
        return [
            'from_city_id.exists'           =>      'From.City.ID does not exist in our system',
            'destination_city_id.exists'    =>      'Destination.City.ID does not exist in our system',
            'destination_city_id.different' =>      'Destination City must be different from From City',
            'destination_city_id.required'  =>      'Destination.City.ID is required',
            'arrival_date.date'             =>      'Arrival date is not valid',
            'arrival_date.date_format'      =>      'Arrival date format is not in valid format',
            'arrival_date.required'         =>      'Arrival date is required'
        ];
    }

}
