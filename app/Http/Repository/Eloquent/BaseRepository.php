<?php

namespace App\Http\Repository\Eloquent;

use App\Http\Repository\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
     protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
    * @param $id
    * @return Model
    */
    public function find($id): ? Model
    {
        return $this->model->find($id);
    }
}
