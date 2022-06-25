<?php

namespace App\Services;

use App\Http\Traits\ServiceTrait;
use App\Models\Candidate;
use App\Models\CandidateMeeting;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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

        $candidates = Candidate::mapInformation($candidates);

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

    public function connectToZoom(Request $request)
    {
        $validatedData = $this->validate($request);

        $base64_authorization = base64_encode(env('ZOOM_CLIENT_ID') . ":" . env("ZOOM_CLIENT_SECRET"));

        $query = "code={$validatedData['code']}&grant_type=authorization_code&redirect_uri=" . env("ZOOM_REDIRECT_URI");
        $url = "https://zoom.us/oauth/token?$query";

        $response = Http::withHeaders(['Authorization' => "Basic $base64_authorization",
            'Content-Type' => "application/x-www-form-urlencoded"])->post($url);

        if ($response->status() == 200) {
            $data = collect(json_decode($response->body()))->toArray();
            auth()->user()->zoom_information()->create($data);

            return $this->success([], "Connected Successfully");
        } else {
            return $this->error(["Error while connection to zoom"], 500);
        }
    }

    public function checkIfConnectedToZoom()
    {
        return $this->success(["is_connected" => auth()->user()->zoom_information()->exists(), "Checked"]);
    }
}
