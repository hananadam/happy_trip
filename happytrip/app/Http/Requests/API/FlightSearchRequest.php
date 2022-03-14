<?php

namespace App\Http\Requests\API;

class FlightSearchRequest extends FormRequest
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
            "fly_from" => "required",
            "fly_to" => "required",
            "date_from" => "required|date|after:now",
            "date_to" => "required|date|after:now",

            "return_from" => "required_if:flight_type,round|date|after:now",
            "return_to" => "required_if:flight_type,round|date|after:now",
//            "nights_in_dst_from" => "2",
//            "nights_in_dst_to" => "3",
//            "max_fly_duration" => "20",
            "flight_type" => "required|string|in:round,oneway",
//            "one_for_city" => "0",
//            "one_per_date" => "0",
//            "adults" => "2",
//            "children" => "2",
//            "selected_cabins" => "C",
//            "mix_with_cabins" => "M",
//            "adult_hold_bag" => "1,0",
//            "adult_hand_bag" => "1,1",
//            "child_hold_bag" => "2,1",
//            "child_hand_bag" => "1,1",
//            "only_working_days" => "false",
//            "only_weekends" => "false",
//            "partner_market" => "us",
//            "max_stopovers" => "2",
//            "max_sector_stopovers" => "2",
//            "vehicle_type" => "aircraft",
//            "limit" => "500"
        ];
    }
}
