<?php

namespace App\Actions;

use App\Models\RatingItem;

abstract class DeleteRatingItemsAction
{
    public static function refreshRatingItems(RatingItem $ratingItem)
    {
        $ratingItem->delete();
//        $yesterday = Carbon::yesterday();
//        RatingProduct::where('created_at', '<' ,$yesterday )->delete();
    }
}
