<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMeetingRequest;
use App\Models\Candidate;
use App\Models\CandidateMeeting;
use App\Services\CandidateMeetingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ZoomMeetingController extends Controller
{

    protected $service;

    /**
     * @param CandidateMeetingService $service
     */
    public function __construct(CandidateMeetingService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return $this->service->get($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateMeetingRequest $request
     * @param Candidate $candidate
     * @return JsonResponse
     */
    public function store(CreateMeetingRequest $request, Candidate $candidate)
    {
        return $this->service->create($request, $candidate);
    }

    /**
     * Display the specified resource.
     *
     * @param CandidateMeeting $meeting
     * @return JsonResponse
     */
    public function show(CandidateMeeting $meeting)
    {
        return $this->service->view($meeting);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($meeting_id)
    {
        return $this->service->delete($meeting_id);
    }
}
