<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Transfer\DeleteRequest;
use App\Http\Requests\Api\Transfer\StoreRequest;
use App\Services\Api\TransferService;

class TransferController extends Controller {

    protected $service;

    public function __construct(TransferService $transferService) {
        $this->service = $transferService;
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