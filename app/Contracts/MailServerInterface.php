<?php

namespace App\Contracts;

use App\Models\Language;

interface MailServerInterface
{
    public function connect();
    public function runJob(Language $language, $method);
    public function fetch(Language $language);
    public function fetchBySubject(string $subject, Language $language);
    public function fetchByDate($date, Language $language);
    public function fetchByEmail(string $email, Language $language);
    public function fetchAttachments($message);
}
