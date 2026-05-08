<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameAccountRequest extends FormRequest
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
            'userid'                =>  'required|unique:login|min:5',
            'password'              =>  'required|confirmed|min:5',
            'masterAccountPassword' =>  'required|min:5',
        ];
    }

    public function messages()
    {
        return [
            'userid.required' => 'Please enter a userid',
            'userid.unique' => 'Userid already exists',
            'userid.min' => 'Userid must be at least 5 characters',
            'masterAccountPassword.required' => 'Please enter a master account password',
            'masterAccountPassword.min' => 'Master account password must be at least 5 characters',
        ];
    }
}
