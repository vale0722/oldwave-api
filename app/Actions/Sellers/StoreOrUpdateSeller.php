<?php

namespace App\Actions\Sellers;

use App\Actions\StoreOrUpdateModel;
use App\Helpers\FilesHelper;
use App\Models\Seller;
use Illuminate\Support\Arr;

class StoreOrUpdateSeller extends StoreOrUpdateModel
{
    public function execute(): self
    {
        /** @var Seller model */
        $this->model = $this->model ?? new Seller();
        $this->model->name = $this->data['name'];

        if (Arr::has($this->data, 'url')) {
            $file = FilesHelper::save('images', Arr::get($this->data, 'logo'));
            $this->model->logo = $file;
        }
        $this->model->save();

        return $this;
    }
}
