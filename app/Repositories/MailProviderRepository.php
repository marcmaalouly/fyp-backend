<?php

namespace App\Repositories;

use App\Contracts\MailServerInterface;
use App\Jobs\FetchSMTPEmails;
use Illuminate\Support\Facades\Cache;

abstract class MailProviderRepository implements MailServerInterface
{
    abstract public function connect(string $username = null, string $password = null);

    public function fetch($folder = null)
    {
        $client = Cache::get('client_' . auth()->user()->email);

        if (!$client->isConnected()) {
            $client = $client->connect();
        }

        $job = new FetchSMTPEmails($client, $folder);
        dispatch($job);
    }

    public function fetchBySubject(string $subject)
    {
        // TODO: Implement fetchBySubject() method.
    }

    public function fetchByDate($date)
    {
        // TODO: Implement fetchByDate() method.
    }

    public function fetchByEmail(string $email)
    {
        // TODO: Implement fetchByEmail() method.
    }

    public function fetchAttachments($message)
    {
        // TODO: Implement fetchAttachments() method.
    }
}
