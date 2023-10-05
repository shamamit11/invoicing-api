<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\Site\CommonService;

class CommonController extends Controller
{
    protected $common;

    public function __construct(CommonService $CommonService)
    {
        $this->common = $CommonService;
    }

    public function verifyUserEmail($token)
    {
        return $this->common->verifyUserEmail($token);
    }

    public function userVerified()
    {
        return view('verification.user-verified');
    }

    public function userNotVerified()
    {
        return view('verification.user-not-verified');
    }
}