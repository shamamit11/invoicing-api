<?php

namespace App\Http\Requests\Api\Organization;
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
            'org_name' => 'required',
            'org_email' => 'required|email',
            'org_phone' => 'required',
            'org_website' => 'nullable',
            'org_address' => 'required',
            'org_address_1' => 'nullable',
            'org_address_2' => 'nullable',
            'org_city' => 'required',
            'org_country' => 'required',
            'org_license_no' => 'nullable',
            'org_logo' => 'nullable',
            'org_signature' => 'nullable',
            'org_stamp' => 'nullable',
            'org_trn_no' => 'nullable',
            'org_terms_conditions' => 'nullable',
            'org_currency' => 'required',
            'tax_percent' => 'nullable'
        ];
    }
}
