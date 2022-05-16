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

    public function runJob(Language $language, $method)
    {
        $job = new FetchGmailEmails($language->id, auth()->user(), $method);
        dispatch($job);
    }

    public function fetch(Language $language)
    {
        $parameters = [
            'method' => __FUNCTION__
        ];

        $this->runJob($language, $parameters);
    }

    public function fetchBySubject(string $subject, Language $language)
    {
        $parameters = [
            'method' => __FUNCTION__,
            'content' => $subject
        ];

        $this->runJob($language, $parameters);
    }

    public function fetchByDate($date, Language $language)
    {
        $parameters = [
            'method' => __FUNCTION__,
            'content' => $date
        ];

        $this->runJob($language, $parameters);
    }

    public function fetchByEmail(string $email, Language $language)
    {
        $parameters = [
            'method' => __FUNCTION__,
            'content' => $email
        ];

        $this->runJob($language, $parameters);
    }

    public function fetchAttachments($message)
    {
        // TODO: Implement fetchAttachments() method.
    }
}
