<?php

namespace App\Services;

use App\Contracts\MailServerInterface;
use App\Http\Traits\ServiceTrait;
use App\Models\Language;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
            $validatedData['username'] ?? null,
            $validatedData['password'] ?? null,
            $validatedData['host'] ?? null,
            $validatedData['port'] ?? null
        );

        Cache::put('client_' . auth()->user()->email, $client);
    }

    public function fetch(Position $position, Language $language)
    {
        $folder = $language->folder ?? ($position->folder ?? 'INBOX');
        $this->repository->fetch($language, $folder);
    }

    public function fetchByEmail(Position $position, Language $language, Request $request)
    {
        $validatedData = $this->validate($request);
        $this->repository->fetchByEmail($validatedData['email'], $position, $language);
    }
}
