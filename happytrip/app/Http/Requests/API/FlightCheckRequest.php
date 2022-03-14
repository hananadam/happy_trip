<?php

namespace App\Http\Requests\API;

class FlightCheckRequest extends FormRequest
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
            "booking_token" => "required|string",
            "bnum" => "required|integer",
            "pnum" => "required|integer",
            "currency" => "nullable|string|min:3|max:3",
            "visitor_uniqid" => "nullable|string",
            "adults" => "nullable|integer",
            "children" => "nullable|integer",
            "infants" => "nullable|integer",
        ];
    }
}
