<?php

namespace App\Http\Requests\API;

class FlightMultiSearchRequest extends FormRequest
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
            "requests"  =>  "required|array",
            "requests.*.to" =>  "required|string|min:3|max:3" ,
            "requests.*.flyFrom" =>  "required|string|min:3|max:3",
            "requests.*.directFlights" =>  "required|int",
            "requests.*.dateFrom"   =>  "required|date|after:now",
            "requests.*.dateTo" =>  "required|date|after:now"
        ];
    }
}
