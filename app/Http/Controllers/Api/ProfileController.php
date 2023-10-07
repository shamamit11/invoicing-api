<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\ProfileStoreRequest;
use App\Http\Requests\Api\User\PasswordStoreRequest;
use App\Services\Api\UserService;

class ProfileController extends Controller
{
    protected $user;
    public function __construct(UserService $userService) {
        $this->user = $userService;
    }
    public function profile()
    {   
        return $this->user->profile();
    } 

    public function store(ProfileStoreRequest $request)
    {
        return $this->user->store($request->validated());
    }

    public function updatePassword(PasswordStoreRequest $request)
    {
        return $this->user->updatePassword($request->validated());
    }
}
