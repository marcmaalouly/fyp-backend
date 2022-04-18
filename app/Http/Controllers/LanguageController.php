<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\StoreLanguageSkillsRequest;
use App\Models\Language;
use App\Models\Position;
use App\Services\LanguageService;
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
     * @return Response
     */
    public function index(Position $position)
    {
        $response = $this->service->get($position);
        return $this->{$response['status']}($response['data'], $response['message'] ?? null, $response['code'] ?? 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLanguageRequest $request
     * @param Position $position
     * @return Response
     */
    public function store(StoreLanguageRequest $request, Position $position)
    {
        $response = $this->service->store($request, $position);
        return $this->{$response['status']}($response['data'], $response['message'] ?? null, $response['code'] ?? 200);
    }

    public function storeSkills(StoreLanguageSkillsRequest $request, Position $position, Language $language)
    {
        $response = $this->service->attachSkills($request, $language);
        return $this->{$response['status']}($response['data'], $response['message'] ?? null, $response['code'] ?? 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Position $position, Language $language)
    {
        $response = $this->service->delete($language);
        return $this->{$response['status']}($response['data'], $response['message'] ?? null, $response['code'] ?? 200);
    }
}
