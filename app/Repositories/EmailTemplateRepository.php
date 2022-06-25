<?php

namespace App\Repositories;

use App\Models\EmailTemplate;

class EmailTemplateRepository
{
    protected $model;

    /**
     * @param EmailTemplate $model
     */
    public function __construct(EmailTemplate $model)
    {
        $this->model = $model;
    }
}
