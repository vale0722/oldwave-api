<?php

namespace App\Listeners;

use App\Events\RatingProductEvent;
use App\Models\RatingProduct;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RatingProductListener
{
    public function handle(RatingProductEvent $event): void
    {
        RatingProduct::create([
            'product_id' => $event->product_id,
        ]);
    }
}
