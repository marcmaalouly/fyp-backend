<?php

namespace App\Services;

use App\Models\Position;
use App\Repositories\PositionRepository;
use App\Http\Traits\ServiceTrait;
use Illuminate\Http\Request;

class PositionService
{
    use ServiceTrait;
    protected $repository;

    /**
     * @param PositionRepository $repository
     */
    public function __construct(PositionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get()
    {
        $positions = $this->repository->where(['user_id' => auth()->user()->id])->get(['id', 'name']);
        return $this->success($positions, 'Fetched');
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request);
        $validatedData['user_id'] = auth()->user()->id;

        return $this->success($this->repository->create($validatedData), 'Position Successfully Created');
    }

    public function update(Request $request, Position $position)
    {
        $validatedData = $this->validate($request);
        $this->repository->update($validatedData, $position);

        return $this->success([], 'Position Successfully Updated');
    }

    public function delete(Position $position)
    {
        $this->repository->delete($position);

        return $this->success([], 'Position Successfully Deleted');
    }
}
