<?php

namespace App\Http\Controllers;

use App\Http\Requests\ZoomConnectionRequest;
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

    public function connectToZoom(ZoomConnectionRequest $request)
    {
        return $this->service->connectToZoom($request);
    }

    public function checkIfConnectedToZoom()
    {
        return $this->service->checkIfConnectedToZoom();
    }
}
