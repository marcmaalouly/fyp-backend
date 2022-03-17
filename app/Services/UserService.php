<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{
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
        $validatedData = $this->validatedRequest($request);
        $user = $this->repository->where(['email' => $validatedData['email']])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return [
                'status' => config('response_code.error'),
                'data' => 'Wrong Email or Password',
                'code' => 401
            ];
        }

        $token = $user->createToken($validatedData['email'])->plainTextToken;
        return [
            'status' => config('response_code.success'),
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
        $validatedData = $this->validatedRequest($request);
        $this->repository->create($validatedData);
        return [
            'status' => config('response_code.success'),
            'data' => [],
            'message' => 'Registered Successfully'
        ];
    }

    private function validatedRequest(Request $request)
    {
        return $request->validated();
    }
}
