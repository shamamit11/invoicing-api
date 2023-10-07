<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SavedItem\DeleteRequest;
use App\Http\Requests\Api\SavedItem\StatusRequest;
use App\Http\Requests\Api\SavedItem\StoreRequest;
use App\Services\Api\SavedItemService;

class SavedItemController extends Controller {

    protected $item;

    public function __construct(SavedItemService $savedItemService) {
        $this->item = $savedItemService;
    }

    public function index() {
        return $this->item->list();
    }

    public function show($id) {
        return $this->item->show($id);
    }

    public function store(StoreRequest $request)
    {
        return $this->item->store($request->validated());
    }

    public function updateStatus(StatusRequest $request)
    {
        return $this->item->updateStatus($request->validated());
    }

    public function delete(DeleteRequest $request)
    {
        return $this->item->delete($request->validated());
    }

}