<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Account\DeleteRequest;
use App\Http\Requests\Api\Account\StoreRequest;
use App\Services\Api\AccountService;

class AccountController extends Controller {

    protected $service;

    public function __construct(AccountService $accountService) {
        $this->service = $accountService;
    }

    public function index() {
        return $this->service->list();
    }

    public function show($id) {
        return $this->service->show($id);
    }

    public function store(StoreRequest $request)
    {
        return $this->service->store($request->validated());
    }

    public function delete(DeleteRequest $request)
    {
        return $this->service->delete($request->validated());
    }

}