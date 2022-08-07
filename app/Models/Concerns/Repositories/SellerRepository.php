<?php

namespace App\Models\Concerns\Repositories;

use App\Actions\Sellers\SellerAction;

trait SellerRepository
{
    public static function actions(): SellerAction
    {
        return new SellerAction();
    }
}
