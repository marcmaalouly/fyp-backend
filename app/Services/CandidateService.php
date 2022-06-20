<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Language;
use App\Repositories\CandidateRepository;
use App\Http\Traits\ServiceTrait;
use Illuminate\Http\Request;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

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

    public function get(Request $request, Language $language)
    {
        $orderBy = $request->input('column', 'id'); //Index
        $orderByDir = $request->input('dir', 'asc');
        $length = $request->input('length');
        $searchValue = $request->input('search');
        $start_date = $request->input('startDate');
        $end_date = $request->input('endDate');

        $candidates = tap($this->repository->orderBy($orderBy, $orderByDir)
            ->where('language_id', $language->id)
            ->whereDataTable($searchValue, $start_date, $end_date)
            ->with('attachments')->paginate($length));

        $candidates =  $candidates = Candidate::mapInformation($candidates);

        return new DataTableCollectionResource($candidates);
    }
}
