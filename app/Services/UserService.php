<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    protected $service;

    /**
     * @param UserRepository $service
     */
    public function __construct(UserRepository $service)
    {
        $this->service = $service;
    }

}
