<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'roles' => 'required|array',
            'type' => 'required',

            'firstname' => 'required_unless:type,none|min:2|max:255',
            'lastname' => 'required_unless:type,none|min:2|max:255',
            'office' => 'required_unless:type,none|min:2"max:255',
            'birthdate' => 'required_unless:type,none|date|before:tomorrow',
            'status' => 'required_unless:type,none|integer'
        ];
    }
}
