<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Language;
use App\Models\Position;
use App\Services\CandidateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class CandidateController extends Controller
{
    protected $service;

    /**
     * @param CandidateService $service
     */
    public function __construct(CandidateService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return DataTableCollectionResource
     */
    public function index(Request $request, Position $position, Language $language)
    {
        return $this->service->get($request, $language);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Candidate $candidate
     * @return JsonResponse
     */
    public function destroy(Candidate $candidate)
    {
        return $this->service->delete($candidate);
    }
}
