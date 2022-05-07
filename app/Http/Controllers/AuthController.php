<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckEmailRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Services\UserService;

class AuthController extends Controller
{
    protected $service;

    /**
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function login(LoginRequest $request)
    {
        return $this->service->login($request);
    }

    public function register(RegisterRequest $request)
    {
        return $this->service->register($request);
    }

    public function checkEmail(CheckEmailRequest $request)
    {
        $request->validated();
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        return $this->service->verifyEmail($request);
    }

    public function logout()
    {
        return $this->service->logout();
    }

    public function me()
    {
        return $this->service->me();
    }
}
