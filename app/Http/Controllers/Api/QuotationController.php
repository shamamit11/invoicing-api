<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Quotation\DeleteRequest;
use App\Http\Requests\Api\Quotation\StoreRequest;
use App\Services\Api\QuotationService;

class QuotationController extends Controller {

    protected $quotation;

    public function __construct(QuotationService $quotationService) {
        $this->quotation = $quotationService;
    }

    public function index() {
        return $this->quotation->list();
    }

    public function show($id) {
        return $this->quotation->show($id);
    }

    public function store(StoreRequest $request)
    {
        return $this->quotation->store($request->validated());
    }

    public function delete(DeleteRequest $request)
    {
        return $this->quotation->delete($request->validated());
    }

    public function getPDFLink($qid)
    {
        return $this->quotation->getPDFLink($qid);
    }

    public function sendEmail($qid)
    {
        return $this->quotation->sendEmail($qid);
    }

}