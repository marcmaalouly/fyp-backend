<?php

namespace App\Services;

use App\Models\Language;
use App\Models\Position;
use App\Repositories\LanguageRepository;
use App\Http\Traits\ServiceTrait;
use Illuminate\Http\Request;

class LanguageService
{
    use ServiceTrait;
    protected $repository;

    /**
     * @param LanguageRepository $repository
     */
    public function __construct(LanguageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get(Position $position)
    {
        $languages = $this->repository->where(['position_id' => $position->id])->with('skill_keys')->get();
        return $this->success($languages, 'Languages Successfully Fetched');
    }

    public function store(Request $request, Position $position)
    {
        $validatedData = $this->validate($request);
        $validatedData['position_id'] = $position->id;

        if (!isset($validatedData['mail_service'])) {
            $validatedData['mail_service'] = $position->mail_service;
        }

        $this->repository->create($validatedData);

        return $this->success([], 'Language Successfully Created');
    }

    public function delete(Language $language)
    {
        $this->repository->delete($language);

        return $this->success([], 'Language Successfully Deleted');
    }
}
