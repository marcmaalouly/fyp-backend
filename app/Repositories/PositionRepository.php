<?php

namespace App\Repositories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Builder;

class PositionRepository
{
    protected $model;

    /**
     * @param Position $model
     */
    public function __construct(Position $model)
    {
        $this->model = $model;
    }

    public function where(array $where): Builder
    {
        return $this->model::where($where);
    }

    public function create(array $attributes): Position
    {
        return $this->model::create($attributes);
    }

    public function find($id)
    {
        return $this->model::find($id);
    }

    public function update(array $attributes, Position $position)
    {
        $position->update($attributes);
    }

    public function delete(Position $position)
    {
        $position->delete();
    }
}
