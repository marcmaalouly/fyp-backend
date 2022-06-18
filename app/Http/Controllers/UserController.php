<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $service;

    /**
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function favorites(Request $request)
    {
        return $this->service->favoriteCandidates($request);
    }

    public function storeFavorite(Candidate $candidate)
    {
        return $this->service->storeFavoriteCandidate($candidate);
    }
}
