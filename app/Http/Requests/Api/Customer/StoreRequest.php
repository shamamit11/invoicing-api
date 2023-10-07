<?php

namespace App\Http\Requests\Api\Customer;
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
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address_1' => 'nullable',
            'address_2' => 'nullable',
            'city' => 'nullable',
            'country' => 'nullable',
            'trn_no' => 'nullable',
            'status' => 'nullable',
        ];
    }
}
