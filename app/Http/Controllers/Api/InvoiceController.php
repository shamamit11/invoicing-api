<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Invoice\DeleteRequest;
use App\Http\Requests\Api\Invoice\PaymentRequest;
use App\Http\Requests\Api\Invoice\StoreRequest;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\Api\InvoiceService;

class InvoiceController extends Controller {

    protected $invoice;

    public function __construct(InvoiceService $invoiceService) {
        $this->invoice = $invoiceService;
    }

    public function index() {
        return $this->invoice->list();
    }

    public function show($id) {
        return $this->invoice->show($id);
    }

    public function store(StoreRequest $request)
    {
        return $this->invoice->store($request->validated());
    }

    public function delete(DeleteRequest $request)
    {
        return $this->invoice->delete($request->validated());
    }

    public function storePayment(PaymentRequest $request)
    {
        return $this->invoice->storePayment($request->validated());
    }

    public function getPDFLink($id)
    {
        return $this->invoice->getPDFLink($id);
    }

    public function sendEmail($id)
    {
        return $this->invoice->sendEmail($id);
    }

}