<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EmailSettings\StoreRequest;
use App\Services\Api\EmailSettingService;

class EmailSettingController extends Controller {

    protected $email;

    public function __construct(EmailSettingService $emailSettingService) {
        $this->email = $emailSettingService;
    }

    public function smtp() {
        return $this->email->smtp();
    }

    public function store(StoreRequest $request)
    {
        return $this->email->store($request->validated());
    }

}