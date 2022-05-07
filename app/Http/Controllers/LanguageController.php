<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\StoreLanguageSkillsRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Models\Language;
use App\Models\Position;
use App\Services\LanguageService;
use Google\Service\ShoppingContent\Resource\Pos;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LanguageController extends BaseApiController
{
    protected $service;

    /**
     * @param LanguageService $service
     */
    public function __construct(LanguageService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Position $position)
    {
        return $this->service->get($position);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLanguageRequest $request
     * @param Position $position
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreLanguageRequest $request, Position $position)
    {
        return $this->service->store($request, $position);
    }

    /**
     * @param StoreLanguageSkillsRequest $request
     * @param Position $position
     * @param Language $language
     * @return mixed
     */
    public function storeSkills(StoreLanguageSkillsRequest $request, Position $position, Language $language)
    {
        return $this->service->attachSkills($request, $language);
    }

    /**
     * @param UpdateLanguageRequest $request
     * @param Position $position
     * @param Language $language
     * @return mixed
     */
    public function update(UpdateLanguageRequest $request, Position $position, Language $language)
    {
        return $this->service->update($request, $language);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Position $position, Language $language)
    {
        return $this->service->delete($language);
    }
}
