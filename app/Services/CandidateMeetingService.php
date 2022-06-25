<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\CandidateMeeting;
use App\Repositories\CandidateMeetingRepository;
use App\Http\Traits\ServiceTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CandidateMeetingService
{
    use ServiceTrait;
    protected $repository;

    /**
     * @param CandidateMeetingRepository $repository
     */
    public function __construct(CandidateMeetingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get(Request $request)
    {
        $meetings = auth()->user()->candidate_meetings()->get();
        $meetings = $meetings->map(function ($meeting) use ($request) {
            $meeting_time = Carbon::parse($meeting['start_time'])
                ->timezone($request->input('timezone') ?? 'Asia/Beirut');

            $meeting['start'] = $meeting_time->format('Y-m-d H:m:s');
            $meeting['end'] = $meeting_time->addHour()->format('Y-m-d H:m:s');
            $meeting['allDay'] = false;
            $meeting['editable'] = false;
            return $meeting;
        });

        return $this->success($meetings, "Meetings Fetched");
    }

    public function create(Request $request, Candidate $candidate)
    {
        $validatedData = $this->validate($request);

        $userZoomToken = auth()->user()->zoom_information ? auth()->user()->zoom_information->access_token : null;

        if (!$userZoomToken) {
            return $this->error(["No zoom token on this account"], 500);
        }

        $response = Http::withHeaders(['Authorization' => "Bearer " . $userZoomToken])
            ->post('https://api.zoom.us/v2/users/me/meetings', [
                "start_time" => $validatedData['start_time'],
                "timezone" => "UTC"
            ]);

        if ($response->status() == 200 || $response->status() == 201) {
            $data = collect(json_decode($response->body()))->toArray();

            auth()->user()->candidate_meetings()->create([
                'candidate_id' => $candidate->id,
                'meeting_url' => $data['join_url'],
                'start_meeting_url' => $data['start_url'],
                'meeting_id' => $data['meeting_id'],
                'start_time' => $data['start_time']
            ]);

            return $this->success([], "Meeting Created");
        } else {
            return $this->error(["Error while creating zoom meeting"], 500);
        }
    }

    public function delete($meeting_id)
    {
        $query = auth()->user()->candidate_meetings()->where('meeting_id', $meeting_id);

        if ($query->exists())
        {
            $query->delete();
            $response = Http::delete('https://api.zoom.us/v2/meetings/' .  $meeting_id);

            if ($response->status() == 204)
            {
                return $this->success([], 'Meeting Deleted Successfully');
            }

            return $this->error(['Error While deleting zoom meeting'], 500);
        }

        return $this->error(['No meeting found'], 404);
    }

    public function view(CandidateMeeting $candidateMeeting)
    {
        return $this->success(CandidateMeeting::with('candidate')->find($candidateMeeting->id), ['Information Fetched']);
    }
}
