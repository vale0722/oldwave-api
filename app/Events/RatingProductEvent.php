<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RatingProductEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $product_id;
    public function __construct($product_id)
    {
        $this->product_id = $product_id;
    }

}
