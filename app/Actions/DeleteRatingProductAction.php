<?php

namespace App\Actions;

use App\Models\RatingProduct;
use Carbon\Carbon;

abstract class DeleteRatingProductAction
{
    public static function refreshRatingProducts(RatingProduct $ratingProduct)
    {
        $ratingProduct->delete();
//        $yesterday = Carbon::yesterday();
//        RatingProduct::where('created_at', '<' ,$yesterday )->delete();
    }
}
