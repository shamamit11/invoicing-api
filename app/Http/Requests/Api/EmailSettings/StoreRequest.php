<?php

namespace App\Http\Requests\Api\EmailSettings;
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
            'host' => 'required',
            'port' => 'required',
            'user_name' => 'required',
            'password' => 'nullable',
            'encryption' => 'required',
            'mail_from_address' => 'nullable',
            'mail_from_name' => 'nullable'
        ];
    }
}
