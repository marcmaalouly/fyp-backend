<?php

namespace App\Services;

use App\Contracts\MailServerInterface;
use App\Http\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class MailConnectionService
{
    use ServiceTrait;
    protected $repository;

    /**
     * @param MailServerInterface $repository
     */
    public function __construct(MailServerInterface $repository)
    {
        $this->repository = $repository;
    }

    public function connect(Request $request)
    {
        $validatedData = $this->validate($request);

        $client = $this->repository->connect(
            $validatedData['username'],
            $validatedData['password'],
            $validatedData['host'] ?? null,
            $validatedData['port'] ?? null
        );

        Cache::put('client_' . auth()->user()->email, $client);
    }

    public function fetch()
    {
        $this->repository->fetch();
    }
}
