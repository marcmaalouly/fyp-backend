<?php

namespace App\Repositories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;

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

    public function where(array $where): Builder
    {
        return $this->model::where($where);
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
