<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\CandidateMeeting;
use App\Models\EmailTemplate;
use App\Models\MeetingSchedule;
use App\Notifications\SendZoomURLNotification;
use App\Repositories\CandidateMeetingRepository;
use App\Http\Traits\ServiceTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Notification;

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
        $meetings = auth()->user()->candidate_meetings()->with('candidate')->get();
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

    public function checkAvailability(Request $request)
    {
        $date = $request->input('date') ?? now()->format('Y-m-d');

        $meetingSchedules = MeetingSchedule::all();
        $takenMeetings = auth()->user()->candidate_meetings()->whereDate('start_time', $date)->with('meeting_schedule')->get()->pluck('meeting_schedule.id');

        $meetingSchedule = $meetingSchedules->map(function ($meetingSchedule) use ($takenMeetings) {
            if ($takenMeetings->contains($meetingSchedule->id)) {
                $meetingSchedule['available'] = false;
                return $meetingSchedule;
            }
            $meetingSchedule['available'] = true;
            return $meetingSchedule;
        });

        return $this->success($meetingSchedule, ['Fetched available time']);
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
                'meeting_id' => $data['id'],
                'start_time' => $data['start_time'],
                'meeting_schedule_id' => $validatedData['meeting_schedule_id']
            ]);

            $template = null;
            if (isset($validatedData['template_id'])) {
                $template = EmailTemplate::find($validatedData['template_id']);
            }

            $custom_message = null;
            if (isset($validatedData['custom_message'])) {
                $custom_message = $validatedData['custom_message'];
            }

            Notification::route('mail', $candidate->email)->notify(new SendZoomURLNotification($data, $template, $custom_message));

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
            $userZoomToken = auth()->user()->zoom_information ? auth()->user()->zoom_information->access_token : null;

            if (!$userZoomToken) {
                return $this->error(["No zoom token on this account"], 500);
            }

            $response = Http::withHeaders(['Authorization' => "Bearer " . $userZoomToken])
                ->delete('https://api.zoom.us/v2/meetings/' .  $meeting_id);

            if ($response->status() == 204)
            {
                $query->delete();
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
