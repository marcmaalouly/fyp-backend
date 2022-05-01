<?php

namespace App\Repositories;

use App\Contracts\MailServerInterface;
use App\Jobs\FetchSMTPEmails;
use App\Models\Language;
use Illuminate\Support\Facades\Cache;

abstract class MailProviderRepository implements MailServerInterface
{
    abstract public function connect(string $username = null, string $password = null);

    private function runJob(Language $language, $method, $folder = null)
    {
        $client = Cache::get('client_' . auth()->user()->email);

        if (!$client->isConnected()) {
            $client = $client->connect();
        }

        $job = new FetchSMTPEmails($client, $language->id, $method, $folder);
        dispatch($job);
    }

    public function fetch(Language $language, $folder = null)
    {
        $parameters = [
            'method' => __FUNCTION__
        ];

        $this->runJob($language, $parameters, $folder);
    }

    public function fetchBySubject(string $subject, Language $language = null, $folder = null)
    {
        $parameters = [
            'method' => __FUNCTION__,
            'content' => $subject
        ];

        $this->runJob($language, $parameters, $folder);
    }

    public function fetchByDate($date, Language $language = null, $folder = null)
    {
        $parameters = [
            'method' => __FUNCTION__,
            'content' => $date
        ];

        $this->runJob($language, $parameters, $folder);
    }

    public function fetchByEmail(string $email, Language $language = null, $folder = null)
    {
        $parameters = [
            'method' => __FUNCTION__,
            'content' => $email
        ];

        $this->runJob($language, $parameters, $folder);
    }

    public function fetchAttachments($message)
    {
        // TODO: Implement fetchAttachments() method.
    }
}
