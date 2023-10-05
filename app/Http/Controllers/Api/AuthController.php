<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Services\Api\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller {

    protected $auth;

    public function __construct(AuthService $authService) {
        $this->auth = $authService;
    }

    public function login(LoginRequest $request) {
        return $this->auth->login($request->validated());
    }

    public function register(RegisterRequest $request)
    {
        return $this->auth->register($request->validated());
    }

}