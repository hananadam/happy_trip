<?php

namespace App\Http\Requests\API;

class FlightBookRequest extends FormRequest
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
            "bags" => "required|integer",
            "booking_token" => "required|string",
            "currency" => "required|min:3|max:3",
            "lang" => "required|min:2|max:2",
            "locale" => "required",
            "passengers" => 'required|array',
            "passengers.*.birthday" => "required|date",
            "passengers.*.cardno" => "required|string",
            "passengers.*.category" => "required|string|in:adult,child,infant",
            "passengers.*.email" => "required|email",
            "passengers.*.expiration" => "required|date",
            "passengers.*.name" => "required|string",
            "passengers.*.nationality" => "required|min:2|max:2",
            "passengers.*.phone" => "required|string",
            "passengers.*.surname" => "required|string",
            "passengers.*.title" => "required|in:Mr,Ms,mr,ms",
            "passengers.*.hold_bags" => "array",
            "session_id" => "required|string"
        ];
    }
}
