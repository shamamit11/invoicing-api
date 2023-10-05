<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Organization\StoreRequest;
use App\Services\Api\OrganizationService;
use Illuminate\Http\Request;

class OrganizationController extends Controller {

    protected $organization;

    public function __construct(OrganizationService $organizationService) {
        $this->organization = $organizationService;
    }

    public function organization() {
        return $this->organization->profile();
    }

    public function store(StoreRequest $request)
    {
        return $this->organization->store($request->validated());
    }

}