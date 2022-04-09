<?php

namespace App\Services;

use App\Repositories\CandidateRepository;
use App\Http\Traits\ServiceTrait;

class CandidateService
{
    use ServiceTrait;
    protected $repository;

    /**
     * @param CandidateRepository $repository
     */
    public function __construct(CandidateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createFromEmail($data)
    {
        $this->repository->create($data);
    }
}
