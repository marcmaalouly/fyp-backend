<?php

namespace App\Contracts;

use App\Models\Language;

interface MailServerInterface
{
    public function connect();
    public function fetch(Language $language);
    public function fetchBySubject(string $subject);
    public function fetchByDate($date);
    public function fetchByEmail(string $email);
    public function fetchAttachments($message);
}
