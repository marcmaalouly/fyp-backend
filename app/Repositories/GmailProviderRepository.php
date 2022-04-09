<?php

namespace App\Repositories;

use App\Contracts\MailServerInterface;

class GmailProviderRepository implements MailServerInterface
{
    public function connect()
    {
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
