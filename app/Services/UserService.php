<?php

namespace App\Services;

use App\Http\Traits\ServiceTrait;
use App\Models\Candidate;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

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

    public function favoriteCandidates(Request $request)
    {
        $orderBy = $request->input('column', 'id'); //Index
        $orderByDir = $request->input('dir', 'asc');
        $length = $request->input('length');
        $searchValue = $request->input('search');
        $start_date = $request->input('startDate');
        $end_date = $request->input('endDate');

        $values = [
            'orderBy' => $orderBy,
            'oderByDir' => $orderByDir,
            'length' => $length,
            'search' => $searchValue,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        $candidates = $this->repository->dataTableReturn($values);

        $candidates = $candidates->map(function (Candidate $candidate) {
            $is_favorite = false;
            if ($candidate->favored_by_users()->where('candidate_user.user_id', auth()->user()->id)->exists()) {
                $is_favorite = true;
            }

            $candidate['is_favorite'] = $is_favorite;
            return $candidate;
        });

        return new DataTableCollectionResource($candidates);
    }

    public function storeFavoriteCandidate(Candidate $candidate)
    {
        $query = auth()->user()->favorite_candidates();

        if ($query->where('candidate_user.candidate_id', $candidate->id)->exists()) {
            $query->detach($candidate->id);
            return $this->success([], 'Favorite Removed');
        }

        $query->attach($candidate->id);
        return $this->success([], 'Favorite Stored');
    }
}
