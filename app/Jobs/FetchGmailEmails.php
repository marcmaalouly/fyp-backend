<?php

namespace App\Jobs;

use App\Models\Candidate;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Dacastro4\LaravelGmail\Services\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchGmailEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $language_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($language_id)
    {
        $this->language_id = $language_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var LaravelGmail $messages */
        $messages = LaravelGmail::message()->unread()->preload()->all();
        foreach ( $messages as $message ) {
            /** @var Message\Mail $message */
            $data = [
                'email' => $message->getFromEmail(),
                'mail_content_raw' => $message->getPlainTextBody(),
                'mail_content_html' => $message->getHtmlBody(),
                'language_id' => $this->language_id
            ];

            Candidate::create($data);
        }
    }
}
