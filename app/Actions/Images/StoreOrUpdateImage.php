<?php

namespace App\Actions\Images;

use App\Actions\StoreOrUpdateModel;
use App\Helpers\FilesHelper;
use App\Models\Image;
use Illuminate\Support\Arr;

class StoreOrUpdateImage extends StoreOrUpdateModel
{
    public function execute(): self
    {
        /** @var Image model */
        $this->model = $this->model ?? new Image();
        $this->model->item_id = $this->data['item_id'];

        if (Arr::has($this->data, 'url')) {
            $file = FilesHelper::save('images', Arr::get($this->data, 'url'));
            $this->model->url = $file;
        }
        $this->model->save();

        return $this;
    }
}
