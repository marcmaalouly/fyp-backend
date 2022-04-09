<?php

namespace App\Contracts;

interface MailServerInterface
{
    public function connect();
    public function fetch();
    public function fetchBySubject(string $subject);
    public function fetchByDate($date);
    public function fetchByEmail(string $email);
    public function fetchAttachments($message);
}
