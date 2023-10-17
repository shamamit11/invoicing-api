<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IncomeExpense\DeleteRequest;
use App\Http\Requests\Api\IncomeExpense\StoreRequest;
use App\Services\Api\IncomeExpenseService;

class IncomeExpenseController extends Controller {

    protected $service;

    public function __construct(IncomeExpenseService $incomeExpenseService) {
        $this->service = $incomeExpenseService;
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