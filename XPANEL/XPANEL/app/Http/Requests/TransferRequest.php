<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TransferRequest extends FormRequest
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
    public function rules(Request $request)
    {
        switch ($request->input('points_type')) {
            case 'Donation Points':
                $config = Config::get('xpanel.donations');

                $rules = [
                    'account_id' => ['required'],
                    'amount' => ['required', 'numeric'],
                    'points_type' => ['required'],
                ];

                if ($config['transfer_type'] === 'item')
                    $rules['char_id'] = ['required'];

                return $rules;

            case 'Vote Points':
                return [
                    'account_id' => ['required'],
                    'points_type' => ['required'],
                    'amount' => ['required', 'numeric']
                ];
        }
    }
}
