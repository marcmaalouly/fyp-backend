<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository
{
    protected $model;

    /**
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function where($column, $attribute): UserRepository
    {
        $this->model = $this->model::where($column, $attribute);
        return $this;
    }

    public function first(): User
    {
        return $this->model->first();
    }

    public function create(array $data): User
    {
        return $this->model::create($data);
    }
}
