<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
class PayoutRequest extends FormRequest
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
            'master_id' => ['required'],
            'email' => ['required'],
            'total' => ['required','numeric'],
            'amount' => ['required','numeric','min:1'],
            'master_password' => ['required'],
            'type' => ['required'],
            'terms' => ['required', 'accepted'],
        ];
    }
    
    public function messages()
    {

        return [
            'email.required' => 'Email receiver is required',
            'total.required' => 'Payout amount is required',
            'amount.required' => 'Payout amount is required',
            'amount.min' => 'Payout amount must be greater than 0',
            'master_password.required' => 'Master password is required',
            'type.required' => 'Payout method is required',
            'terms.accepted' => 'You must agree to the terms and conditions',
        ];
    }
}
