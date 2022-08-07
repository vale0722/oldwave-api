<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;

abstract class StoreOrUpdateModel extends Action
{
    protected ?Model $model;
    protected array $data;

    public function __construct(array $data, Model $model = null)
    {
        $this->model = $model;
        $this->data = $data;
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
