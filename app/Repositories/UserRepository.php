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

    public function where(array $where): Builder
    {
        return $this->model::where($where);
    }

    public function create(array $data): User
    {
        return $this->model::create($data);
    }
}
