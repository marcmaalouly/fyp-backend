<?php

namespace App\Jobs;

use App\Helpers\CvParser;
use App\Models\Candidate;
use App\Models\CandidateAttachment;
use App\Models\User;
use App\Notifications\EmailFetchedNotification;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Dacastro4\LaravelGmail\Services\Message;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FetchGmailEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $language_id;
    protected $user;
    protected $parameters;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($language_id, User $user, array $parameters)
    {
        $this->language_id = $language_id;
        $this->user = $user;
        $this->parameters = $parameters;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Auth::login($this->user);

            $messages = $this->fetchMessages();

            $this->readMessages($messages);

            $this->sendNotification();
        } catch (Exception $exception) {
            Log::error($exception);
        }
    }

    /**
     * Choose the method based on the parameter and fetch all emails
     *
     * @return mixed
     */
    private function fetchMessages()
    {
        $initiatedMessage = LaravelGmail::message();

        switch ($this->parameters['method']) {
            case 'fetchBySubject':
                $messages = $initiatedMessage->subject($this->parameters['content']);
                break;
            case 'fetchByDate':
                $messages = $initiatedMessage->after($this->parameters['content']);
                break;
            case 'fetchByEmail':
                $messages = $initiatedMessage->from($this->parameters['content']);
                break;
            default:
                $messages = $initiatedMessage;
                break;
        }

        return $messages->unread()->preload()->all();
    }

    /**
     * Loop through the fetched messages
     * @param $messages
     * @return void
     */
    private function readMessages($messages)
    {
        foreach ( $messages as $message ) {
            /** @var Message\Mail $message */
            if ($message->hasAttachments()) {
                $candidate = $this->createCandidate($message);
                $this->getAndStoreAttachments($message, $candidate);
            }
        }
    }

    /**
     * Create the candidate with all his info after retrieving the email
     *
     * @param Message\Mail $message
     * @return Candidate|Model
     */
    private function createCandidate(Message\Mail $message)
    {
        $data = [
            'email' => $message->getFromEmail(),
            'mail_content_raw' => $message->getPlainTextBody(),
            'mail_content_html' => $message->getHtmlBody(),
            'language_id' => $this->language_id,
            'date' => $message->getDate()
        ];

        return Candidate::create($data);
    }

    /**
     * Fetch the attached files in the email and store them in the public folder
     *
     * @param Message\Mail $message
     * @param Candidate $candidate
     * @return void
     */
    private function getAndStoreAttachments(Message\Mail $message, Candidate $candidate)
    {
        $attachments = $message->getAttachments()->all();
        $flag = false;
        foreach ($attachments as $attachment)
        {
            /** @var Message\Attachment $attachment */
            if($attachment->getMimeType() == 'application/pdf' || str_contains($attachment->getMimeType(), 'pdf'))
            {
                $path = $candidate->language->position->user_id . '/' . $candidate->language_id . '/' . $candidate->email;

                $public_path = public_path('storage/attachments/' . $path . '/');

                if (!File::exists($public_path)) {
                    File::makeDirectory($public_path, 0755, true);
                }

                $attachment->saveAttachmentTo($path, $attachment->getFileName(), 'attachments');
                $flag = true;
            }
        }

        //Check if the candidate has an attached cv, if true keep in the database else delete the candidate
        if ($flag) {
            $this->saveAttachmentInDB($candidate);
        } else {
            $candidate->delete();
        }
    }

    /**
     * Save the files from the public folder into the database and send file to cv parser
     *
     * @param Candidate $candidate
     * @return void
     */
    private function saveAttachmentInDB(Candidate $candidate)
    {
        $files = Storage::disk('attachments')->allFiles($candidate->language->position->user_id . '/' . $candidate->language_id
            . '/' . $candidate->email);

        foreach ($files as $file) {
            CandidateAttachment::create([
                'candidate_id' => $candidate->id,
                'path' => 'storage/attachments/' . $file
            ]);
        }

        CvParser::parse($files, $candidate);
    }

    /**
     * Send a live notification to the user to inform them that their emails were fetched.
     * @return void
     */
    private function sendNotification()
    {
        $this->user->notify(new EmailFetchedNotification());
    }
}
