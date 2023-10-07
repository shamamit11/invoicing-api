<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\DeleteRequest;
use App\Http\Requests\Api\Customer\StatusRequest;
use App\Http\Requests\Api\Customer\StoreRequest;
use App\Services\Api\CustomerService;

class CustomerController extends Controller {

    protected $customer;

    public function __construct(CustomerService $customerService) {
        $this->customer = $customerService;
    }

    public function index() {
        return $this->customer->list();
    }

    public function show($id) {
        return $this->customer->show($id);
    }

    public function store(StoreRequest $request)
    {
        return $this->customer->store($request->validated());
    }

    public function updateStatus(StatusRequest $request)
    {
        return $this->customer->updateStatus($request->validated());
    }

    public function delete(DeleteRequest $request)
    {
        return $this->customer->delete($request->validated());
    }

}