<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RatingItemEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public int $item_id;

    public function __construct(int $item_id)
    {
        $this->item_id = $item_id;
    }
}
