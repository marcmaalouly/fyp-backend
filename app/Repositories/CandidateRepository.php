<?php

namespace App\Repositories;

use App\Models\Candidate;

class CandidateRepository
{
    protected $model;

    /**
     * @param Candidate $model
     */
    public function __construct(Candidate $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes)
    {
        return $this->model::create($attributes);
    }
}
