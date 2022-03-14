<?php

namespace App\Http\Requests\API;

class HotelSearchRequest extends FormRequest
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
            'destinationCode' => 'required|string|between:2,100',
            'checkIn' => 'required|date_format:Y-m-d',
            'checkOut' => 'required|date_format:Y-m-d',
            'room_num' => 'required',
            'adults_num' => 'required',
            // 'ages' => 'required',
           // 'bed' => 'required',
        ];
    }
}
