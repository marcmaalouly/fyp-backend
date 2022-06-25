<?php

namespace App\Services;

use App\Repositories\EmailTemplateRepository;
use App\Http\Traits\ServiceTrait;
use Illuminate\Http\Request;

class EmailTemplateService
{
    use ServiceTrait;
    protected $repository;

    /**
     * @param EmailTemplateRepository $repository
     */
    public function __construct(EmailTemplateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return $this->success(auth()->user()->email_templates()->get(), 'Templates Fetched');
    }

    public function create(Request $request)
    {
        $validatedData = $this->validate($request);

        auth()->user()->email_templates()->create($validatedData);

        return $this->success([], 'Template Created');
    }
}
