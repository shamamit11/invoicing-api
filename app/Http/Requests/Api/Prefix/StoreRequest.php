<?php

namespace App\Http\Requests\Api\Prefix;
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
            'receipt_prefix' => 'required',
            'receipt_start_no' => 'required|numeric',
            'quote_prefix' => 'required',
            'quote_start_no' => 'required|numeric',
            'invoice_prefix' => 'required',
            'invoice_start_no' => 'required|numeric',
        ];
    }
}
