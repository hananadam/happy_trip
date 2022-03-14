<?php

namespace App\Http\Requests\Auth;

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
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users,email|unique:customers,email',
            'password' => 'required|string|confirmed|min:6',
            'password_confirmation' => 'required',
            'gender' => 'required',
            'country' => 'required',
            'address' => 'required|string',
            'mobile_num' => 'required',
            'nationality' => 'required',
            // 'id_number' => 'required',
            // 'id_expiry_date' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
