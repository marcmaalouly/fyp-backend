<?php

namespace App\Jobs;

use App\Helpers\CvParser;
use App\Models\Candidate;
use App\Models\CandidateAttachment;
use App\Models\User;
use App\Notifications\EmailFetchedNotification;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Webklex\PHPIMAP\Attachment;
use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Message;

class FetchSMTPEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $oClient, $folder, $user, $language_id, $parameters = [];

    /**
     * @param Client $oClient
     */
    public function __construct(Client $oClient, $language_id, array $parameters, User $user, $folder = 'INBOX')
    {
        $this->oClient = $oClient;
        $this->folder = $folder;
        $this->language_id = $language_id;
        $this->parameters = $parameters;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            ClientManager::$config = config('imap');
            $oFolder = $this->oClient->getFolder($this->folder);

            $messages = $this->fetchMessages($oFolder);

            $this->readMessages($messages);

            $this->sendNotification();
        } catch (Exception $exception) {
            Log::error($exception);
        }
    }

    /**
     * Choose the method based on the parameter and fetch all emails
     *
     * @param $oFolder
     * @return mixed
     */
    private function fetchMessages($oFolder)
    {
        $initiatedMessage = $oFolder->query();
        switch ($this->parameters['method']) {
            case 'fetchBySubject':
                $messages = $initiatedMessage->subject($this->parameters['content'])->get();
                break;
            case 'fetchByDate':
                $messages = $initiatedMessage->on($this->parameters['content'])->get();
                break;
            case 'fetchByEmail':
                $messages = $initiatedMessage->from($this->parameters['content'])->get();
                break;
            default:
                $messages = $initiatedMessage->unseen()->get();
                break;
        }

        return $messages;
    }

    /**
     * Loop through the fetched messages
     * @param $messages
     * @return void
     */
    private function readMessages($messages)
    {
        foreach ($messages as $message) {
            if ($message->hasAttachments()) {
                $candidate = $this->createCandidate($message);
                $this->getAndStoreAttachments($message, $candidate);
            }
        }
    }

    /**
     * Create the candidate with all his info after retrieving the email
     *
     * @param Message $message
     * @return Candidate|\Illuminate\Database\Eloquent\Model
     */
    private function createCandidate(Message $message)
    {
        $data = [
            'email' => $message->getFrom()[0]->mail,
            'mail_content_raw' => $message->getTextBody(),
            'mail_content_html' => $message->getHTMLBody(true),
            'language_id' => $this->language_id
        ];

        return Candidate::create($data);
    }

    /**
     * Fetch the attached files in the email and store them in the public folder
     *
     * @param Message $message
     * @param Candidate $candidate
     * @return void
     */
    private function getAndStoreAttachments(Message $message, Candidate $candidate)
    {
        $attachments = $message->getAttachments()->all();
        foreach ($attachments as $attachment) {
            /** @var Attachment $attachment */
            if ($attachment->getExtension() == 'pdf' || $attachment->getExtension() == 'docx') {
                $path = public_path('storage/attachments/' . $candidate->language->position->user_id . '/' .
                    $candidate->language_id . '/' . $candidate->email . '/');

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0755, true);
                }

                $attachment->save($path);
            }
        }

        $this->saveAttachmentInDB($candidate);
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
