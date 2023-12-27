<?php

namespace MoamalatPay\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class GenerateSecureKeyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'MID' => 'required',
            'TID' => 'required',
            'amount' => 'required|integer|min:1',
            'merchantReference' => 'nullable',
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {

                if (! $validator->errors()->has('MID') && $this->MID != config('moamalat-pay.merchant_id')) {
                    $validator->errors()->add('MID', 'The MID is incorrect');
                }

                if (! $validator->errors()->has('TID') && $this->TID != config('moamalat-pay.terminal_id')) {
                    $validator->errors()->add('TID', 'The TID is incorrect');
                }
            },
        ];
    }

    public function attributes()
    {
        return [
            'MID' => 'MID',
            'TID' => 'TID',
        ];
    }
}
