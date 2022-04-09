<?php

namespace App\Jobs;

use App\Contracts\MailServerInterface;
use App\Models\Candidate;
use App\Repositories\CandidateRepository;
use App\Services\CandidateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\ClientManager;

class FetchSMTPEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $oClient;

    /**
     * @param Client $oClient
     */
    public function __construct(Client $oClient)
    {
        $this->oClient = $oClient;
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
            $oFolder = $this->oClient->getFolder('INBOX');
            $messages = $oFolder->query()->unseen()->get();
            foreach ($messages as $message)
            {
                $data = [
                    'email' => $message->getFrom()[0]->mail,
                    'mail_content_raw' => $message->getTextBody(),
                    'mail_content_html' => $message->getHTMLBody(true),
                ];

                Candidate::create($data);
            }
        } catch (\Exception $exception) {
            dd($exception);
        }

    }
}
