<?php

namespace App\Http\Requests\API;

class CreditRequest extends FormRequest
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
            'name' => 'required|string|between:2,200',
            'number' => 'required|unique:customer_cards',
            'expire_month' => 'required',
            'expire_year' => 'required',
            'cvv' => 'required',
        ];
    }
}
