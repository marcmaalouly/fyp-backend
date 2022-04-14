<?php

namespace App\Repositories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class LanguageRepository
{
    protected $model;

    /**
     * @param Language $model
     */
    public function __construct(Language $model)
    {
        $this->model = $model;
    }

    public function where(array $where): LanguageRepository
    {
        $this->model = $this->model::where($where);
        return $this;
    }

    public function with($relation): LanguageRepository
    {
        $this->model = $this->model->with($relation);
        return $this;
    }

    public function get(array $columns = null): Collection
    {
        return $this->model->get();
    }

    public function create(array $attributes): Language
    {
        return $this->model::create($attributes);
    }

    public function delete(Language $language)
    {
        $language->delete();
    }
}
