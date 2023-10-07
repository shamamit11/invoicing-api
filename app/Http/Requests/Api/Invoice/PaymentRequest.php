<?php

namespace App\Http\Requests\Api\Invoice;
use App\Http\Requests\ApiRequest;

class PaymentRequest extends ApiRequest
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
            'invoice_id' => 'required',
            'description' => 'required',
            'paid_amount' => 'required',
            'paid_date' => 'required',
        ];
    }
}
