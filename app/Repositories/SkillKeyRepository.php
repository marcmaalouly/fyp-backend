<?php

namespace App\Repositories;

use App\Models\SkillKey;
use Illuminate\Database\Eloquent\Collection;

class SkillKeyRepository
{
    protected $model;

    /**
     * @param SkillKey $model
     */
    public function __construct(SkillKey $model)
    {
        $this->model = $model;
    }

    public function all(array $column = []): Collection
    {
        return $this->model::all($column);
    }

    public function create(array $attributes)
    {
        return $this->model::create($attributes);
    }

    public function get(array $columns = []): Collection
    {
        return $this->model->get($columns);
    }

    public function where($column, $attribute): SkillKeyRepository
    {
        $this->model = $this->model::where($column, $attribute);
        return $this;
    }

    public function whereLike($column, $search): SkillKeyRepository
    {
        $this->model = $this->model::where($column, "LIKE","%$search%");
        return $this;
    }

    public function count()
    {
        return $this->model::count();
    }
}
