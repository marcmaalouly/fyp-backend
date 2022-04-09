<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;
use Webklex\PHPIMAP\ClientManager;

class SMTPProviderAdapter extends MailProviderRepository
{

    public function connect(string $username = null, string $password = null, string $host = null, int $port = null)
    {
        $oClient = \Webklex\IMAP\Facades\Client::make([
            'host'          => $host,
            'port'          => $port,
            'encryption'    => 'ssl',
            'validate_cert' => true,
            'username'      => $username,
            'password'      => $password,
            'protocol'      => 'imap'
        ]);

        return $oClient->connect();
    }
}
