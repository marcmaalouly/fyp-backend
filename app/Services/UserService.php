<?php

namespace App\Services;

use App\Http\Traits\ServiceTrait;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{
    use ServiceTrait;
    protected $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function login(Request $request)
    {
        $validatedData = $this->validate($request);
        $user = $this->repository->where(['email' => $validatedData['email']])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return [
                'status' => $this->error(),
                'data' => 'Wrong Email or Password',
                'code' => 401
            ];
        }

        $token = $user->createToken($validatedData['email'])->plainTextToken;
        return [
            'status' => $this->success(),
            'data' => [
                'token_type' => 'bearer',
                'token' => $token,
                'user' => $user,
            ],
            'message' => 'Logged In'
        ];
    }

    public function register(Request $request)
    {
        $validatedData = $this->validate($request);
        $this->repository->create($validatedData);
        return [
            'status' => $this->success(),
            'data' => [],
            'message' => 'Registered Successfully'
        ];
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return [
            'status' => $this->success(),
            'data' => [],
            'message' => 'Logged Out Successfully'
        ];
    }

    public function me()
    {
        if (auth()->check()) {
            return [
                'status' => $this->success(),
                'data' => [],
                'message' => 'Valid Token'
            ];
        }

        return [
            'status' => $this->error(),
            'data' => 'No Longer Authenticated',
            'code' => 401
        ];
    }
}
