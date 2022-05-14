<?php

namespace App\Repositories;

use App\Contracts\MailServerInterface;
use App\Jobs\FetchGmailEmails;
use App\Models\Language;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;

class GmailProviderRepository implements MailServerInterface
{
    public function connect()
    {
        LaravelGmail::makeToken();
    }

    public function fetch(Language $language)
    {
        $job = new FetchGmailEmails($language->id, auth()->user());
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
