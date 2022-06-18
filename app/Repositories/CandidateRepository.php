<?php

namespace App\Repositories;

use App\Models\Candidate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class CandidateRepository
{
    protected $model;

    /**
     * @param Candidate $model
     */
    public function __construct(Candidate $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes)
    {
        return $this->model::create($attributes);
    }

    public function where($column, $attribute, $operation = null): CandidateRepository
    {
        if ($operation) {
            $this->model = $this->model->where($column, $operation, $attribute);
        } else {
            $this->model = $this->model->where($column, $attribute);
        }
        return $this;
    }

    public function orderBy($column, $direction): CandidateRepository
    {
        $this->model = $this->model::orderBy($column, $direction);
        return $this;
    }

    public function whereDataTable($search, $start_date = null, $end_date = null)
    {
        if ($start_date && $end_date) {
            $this->model = $this->model->whereBetween('date', [$start_date, $end_date]);
        }

        $this->model = $this->model->where(function (Builder $query) use ($search, $start_date, $end_date) {
                return $query->where('full_name', "like", "%{$search}%")
                    ->orWhere('email', "like", "%{$search}%");
        });
        return $this;
    }

    public function paginate($length = 10)
    {
        return $this->model->paginate($length);
    }

    public function with($relation): CandidateRepository
    {
        $this->model = $this->model->with($relation);
        return $this;
    }

    public function get()
    {
        return $this->model->get();
    }
}
