<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;

abstract class Action
{
    public ?Model $model;
    public array $data;

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
