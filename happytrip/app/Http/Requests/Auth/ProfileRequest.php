<?php

namespace App\Http\Requests\Auth;
use App\Models\User;
use Illuminate\Validation\Rule;
class ProfileRequest extends FormRequest
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
            'name' => 'string|between:2,100',
            'email' => [
                'email', Rule::unique((new User)->getTable())->ignore(auth()->user()->id ?? null)
            ],
            'password' => 'string|min:6',
            'mobile' => 'numeric',
        ];
    }
}
