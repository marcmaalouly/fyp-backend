<?php

namespace App\Repositories;

use App\Models\CandidateMeeting;

class CandidateMeetingRepository
{
    protected $model;

    /**
     * @param CandidateMeeting $model
     */
    public function __construct(CandidateMeeting $model)
    {
        $this->model = $model;
    }
}
