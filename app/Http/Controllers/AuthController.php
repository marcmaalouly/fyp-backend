<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;

class AuthController extends BaseApiController
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
        $response = $this->service->login($request);
        return $this->{$response['status']}($response['data'], $response['message'] ?? null, $response['code'] ?? 200);
    }

    public function register(RegisterRequest $request)
    {
        $response = $this->service->register($request);
        return $this->{$response['status']}($response['data'], $response['message'] ?? null, $response['code'] ?? 200);
    }

    public function logout()
    {
        $response = $this->service->logout();
        return $this->{$response['status']}($response['data'], $response['message'] ?? null, $response['code'] ?? 200);
    }

    public function me()
    {
        $response = $this->service->me();
        return $this->{$response['status']}($response['data'], $response['message'] ?? null, $response['code'] ?? 200);
    }
}
