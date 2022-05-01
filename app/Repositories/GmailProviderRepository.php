<?php

namespace App\Repositories;

use App\Contracts\MailServerInterface;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;

class GmailProviderRepository implements MailServerInterface
{
    public function connect()
    {
        LaravelGmail::makeToken();
        // TODO: Implement connect() method.
    }

    public function fetch()
    {
        // TODO: Implement fetch() method.
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
