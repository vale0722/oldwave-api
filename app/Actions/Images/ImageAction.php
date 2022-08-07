<?php

namespace App\Actions\Images;

use Illuminate\Database\Eloquent\Model;

class ImageAction
{
    public function storeOrUpdate(array $data, Model $model = null): StoreOrUpdateImage
    {
        return (new StoreOrUpdateImage($data,$model))->execute();
    }
}
