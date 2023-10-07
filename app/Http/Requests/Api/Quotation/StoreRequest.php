<?php

namespace App\Http\Requests\Api\Quotation;
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
            'customer_id' => 'required',
            'quote_no' => 'required',
            'date' => 'required',
            'tax_percent' => 'required',
            'terms_conditions' => '',
            'quote_items' => 'required|array|min:1'
        ];
    }
}
