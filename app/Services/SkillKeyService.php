<?php

namespace App\Services;

use App\Helpers\FileHelper;
use App\Imports\SkillImport;
use App\Repositories\SkillKeyRepository;
use App\Http\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class SkillKeyService
{
    use ServiceTrait;
    protected $repository;

    /**
     * @param SkillKeyRepository $repository
     */
    public function __construct(SkillKeyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get(Request $request)
    {
        return $this->success(
            $this->repository->whereLike('key', $request->input('search'))->get(['id', 'key']),
            'Fetched'
        );
    }
}
