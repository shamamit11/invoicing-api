<?php

namespace App\Http\Requests\Api\Receipt;
use App\Http\Requests\ApiRequest;

class StoreRequest extends ApiRequest
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
            'id' => 'sometimes',
            'customer_id' => 'required',
            'receipt_no' => 'required',
            'date' => 'required',
            'payment_method' => 'required',
            'total_amount' => 'required',
            'paid_for' => 'required',
        ];
    }
}
