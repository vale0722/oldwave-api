<?php

namespace App\Actions\Sellers;

use Illuminate\Database\Eloquent\Model;

class SellerAction
{
    public function storeOrUpdate(array $data, Model $model = null): StoreOrUpdateSeller
    {
        return (new StoreOrUpdateSeller($data,$model))->execute();
    }
}
