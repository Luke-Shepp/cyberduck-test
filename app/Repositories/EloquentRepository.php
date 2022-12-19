<?php

namespace App\Repositories;

use App\Repositories\Contracts\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class EloquentRepository implements Repository
{
    /**
     * @param Model $model
     */
    public function __construct(
        private Model $model
    ) {
        //
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * @param array $with
     * @return Collection
     */
    public function all(array $with = []): Collection
    {
        return $this->model->query()->with($with)->get();
    }
}
