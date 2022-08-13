<?php

namespace App\Listeners;

use App\Events\RatingItemEvent;
use App\Models\RatingItem;

class RatingItemListener
{
    public function handle(RatingItemEvent $event): void
    {
        RatingItem::create([
            'item_id' => $event->item_id,
        ]);
    }
}
