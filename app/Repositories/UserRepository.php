<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
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

    public function where(array $where): UserRepository
    {
        $this->model = $this->model::where($where);
        return $this;
    }

    public function dataTableReturn(array $values)
    {
        $query = auth()->user()->favorite_candidates()->orderBy($values['orderBy'], $values['oderByDir']);
        if ($values['start_date'] && $values["end_date"]) {
            $query = $query->whereBetween('date', [$values['start_date'], $values["end_date"]]);
        }

        return tap($query->where(function (Builder $query) use ($values) {
            return $query->where('full_name', "like", "%{$values['search']}%")
                ->orWhere('email', "like", "%{$values['search']}%");
        })->with('attachments')->with([
            "language" => function($query) {
                $query->with('position');
            }
        ])->paginate($values['length']));
    }

    /**
     * @return User|null
     */
    public function first()
    {
        return $this->model->first();
    }

    public function create(array $data): User
    {
        return $this->model::create($data);
    }
}
