<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Models\Position;
use App\Services\PositionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PositionController extends BaseApiController
{
    protected $service;

    /**
     * @param PositionService $service
     */
    public function __construct(PositionService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $response = $this->service->get();
        return $this->{$response['status']}($response['data'], $response['message'] ?? null, $response['code'] ?? 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePositionRequest $request
     * @return Response
     */
    public function store(StorePositionRequest $request)
    {
        $response = $this->service->store($request);
        return $this->{$response['status']}($response['data'], $response['message'] ?? null, $response['code'] ?? 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePositionRequest $request
     * @param Position $position
     * @return Response
     */
    public function update(UpdatePositionRequest $request, Position $position)
    {
        $response = $this->service->update($request, $position);
        return $this->{$response['status']}($response['data'], $response['message'] ?? null, $response['code'] ?? 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Position $position
     * @return Response
     */
    public function destroy(Position $position)
    {
        $response = $this->service->delete($position);
        return $this->{$response['status']}($response['data'], $response['message'] ?? null, $response['code'] ?? 200);
    }
}
