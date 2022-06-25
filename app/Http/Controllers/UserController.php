<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMeetingRequest;
use App\Http\Requests\DeleteMeetingRequest;
use App\Http\Requests\ZoomConnectionRequest;
use App\Models\Candidate;
use App\Models\CandidateMeeting;
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

    public function createMeeting(CreateMeetingRequest $request, Candidate $candidate)
    {
        return $this->service->createMeeting($request, $candidate);
    }

    public function getMeetings(Request $request)
    {
        return $this->service->getMeetings($request);
    }

    public function deleteMeeting($meeting_id)
    {
        return $this->service->deleteMeeting($meeting_id);
    }

    public function viewMeeting(CandidateMeeting $meeting)
    {
        return $this->service->viewMeeting($meeting);
    }
}
