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
        $user = $this->repository->where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return $this->error('Wrong Email or Password');
        }

        $token = $user->createToken($validatedData['email'])->plainTextToken;
        return $this->success([
            'token_type' => 'bearer',
            'token' => $token,
            'user' => $user,
        ], 'Logged In');
    }

    public function register(Request $request)
    {
        $validatedData = $this->validate($request);
        $this->repository->create($validatedData);
        return $this->success([], 'Registered Successfully');
    }

    public function verifyEmail(Request $request)
    {
        $validatedData = $this->validate($request);

        $user = $this->repository->where(['email' => $validatedData['email'], 'otp' => $validatedData['otp']])->first();

        if (!$user) {
            return $this->error("No OTP found");
        }

        return $this->success([
            'token' => $user->createToken($user->email)->plainTextToken,
            'user' => $user
        ], 'Verified Successfully');
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return $this->success([], 'Logged Out Successfully');
    }

    public function me()
    {
        if (auth()->check()) {
            return $this->success([], 'Valid Token');
        }

        return $this->error('No Longer Authenticated');
    }
}
