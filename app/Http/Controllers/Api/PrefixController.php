<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Prefix\StoreRequest;
use App\Services\Api\PrefixService;

class PrefixController extends Controller {

    protected $prefix;

    public function __construct(PrefixService $prefixService) {
        $this->prefix = $prefixService;
    }

    public function prefix() {
        return $this->prefix->prefix();
    }

    public function store(StoreRequest $request)
    {
        return $this->prefix->store($request->validated());
    }

    public function generateReceiptNo()
    {
        return $this->prefix->generateReceiptNo();
    }

    public function generateQuotationtNo()
    {
        return $this->prefix->generateQuotationtNo();
    }

    public function generateInvoiceNo()
    {
        return $this->prefix->generateInvoiceNo();
    }

}