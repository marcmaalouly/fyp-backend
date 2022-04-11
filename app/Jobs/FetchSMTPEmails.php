<?php

namespace App\Jobs;

use App\Models\Candidate;
use App\Models\CandidateAttachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Message;

class FetchSMTPEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $oClient, $folder, $language_id, $parameters = [];

    /**
     * @param Client $oClient
     */
    public function __construct(Client $oClient, $language_id, array $parameters, $folder = 'INBOX')
    {
        $this->oClient = $oClient;
        $this->folder = $folder;
        $this->language_id = $language_id;
        $this->parameters = $parameters;
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
        $path = public_path('storage/attachments/' . $candidate->email . '/');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $attachments = $message->getAttachments()->all();
        foreach ($attachments as $attachment) {
            $attachment->save($path);
        }

        $this->saveAttachmentInDB($candidate);
    }

    /**
     * Save the files from the public folder into the database
     *
     * @param Candidate $candidate
     * @return void
     */
    private function saveAttachmentInDB(Candidate $candidate)
    {
        $files = Storage::disk('attachments')->allFiles($candidate->email);
        foreach ($files as $file) {
            CandidateAttachment::create([
                'candidate_id' => $candidate->id,
                'path' => 'storage/attachments/' . $file
            ]);
        }
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            ClientManager::$config=config('imap');
            $oFolder = $this->oClient->getFolder($this->folder);

            switch ($this->parameters['method']) {
                case 'fetchBySubject':
                    $messages = $oFolder->query()->subject($this->parameters['content'])->get();
                    break;
                case 'fetchByDate':
                    $messages = $oFolder->query()->on($this->parameters['content'])->get();
                    break;
                case 'fetchByEmail':
                    $messages = $oFolder->query()->from($this->parameters['content'])->get();
                    break;
                default:
                    $messages = $oFolder->query()->unseen()->get();
                    break;
            }

            foreach ($messages as $message)
            {
                $candidate = $this->createCandidate($message);

                if ($message->hasAttachments())
                {
                    $this->getAndStoreAttachments($message, $candidate);
                }
            }

        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }
}
