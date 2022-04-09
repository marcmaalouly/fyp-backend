<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;

class OutlookProviderAdapter extends MailProviderRepository
{

    public function connect(string $username = null, string $password = null)
    {
        $oClient = \Webklex\IMAP\Facades\Client::make([
            'host'          => 'outlook.office365.com',
            'port'          => 993,
            'encryption'    => 'ssl',
            'validate_cert' => true,
            'username'      => $username,
            'password'      => $password,
            'protocol'      => 'imap'
        ]);

        return $oClient->connect();
    }
}
