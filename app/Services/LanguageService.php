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

        if ($validatedData['mail_service'] == 'outlook') {
            $validatedData['folder'] = 'inbox';
        }

        return $this->success($this->repository->create($validatedData), 'Language Successfully Created');
    }

    public function attachSkills(Request $request, Language $language)
    {
        $validatedData = $this->validate($request);
        foreach ($validatedData['skills'] as $skill)
        {
            $language->skill_keys()->attach($skill['id']);
        }
        return $this->success([], 'Skills Successfully attached');
    }

    public function update(Request $request, Language $language)
    {
        $validatedData = $this->validate($request);
        $language->update($validatedData);
        return $this->success([], 'Language Successfully Updated');
    }

    public function delete(Language $language)
    {
        $this->repository->delete($language);

        return $this->success([], 'Language Successfully Deleted');
    }
}
