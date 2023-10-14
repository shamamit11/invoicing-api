<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Receipt\DeleteRequest;
use App\Http\Requests\Api\Receipt\StatusRequest;
use App\Http\Requests\Api\Receipt\StoreRequest;
use App\Services\Api\ReceiptService;

class ReceiptController extends Controller {

    protected $receipt;

    public function __construct(ReceiptService $receiptService) {
        $this->receipt = $receiptService;
    }

    public function index() {
        return $this->receipt->list();
    }

    public function show($id) {
        return $this->receipt->show($id);
    }

    public function store(StoreRequest $request)
    {
        return $this->receipt->store($request->validated());
    }

    public function updateStatus(StatusRequest $request)
    {
        return $this->receipt->updateStatus($request->validated());
    }

    public function delete(DeleteRequest $request)
    {
        return $this->receipt->delete($request->validated());
    }

    public function getPDFLink($rid)
    {
        return $this->receipt->getPDFLink($rid);
    }

    public function sendEmail($rid)
    {
        return $this->receipt->sendEmail($rid);
    }

}