<?php

namespace App\Jobs;

use App\Actions\DeleteRatingItemsAction;
use App\Models\RatingItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefreshRatingItemsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected RatingItem $ratingItem;

    public function __construct(RatingItem $ratingItem)
    {
        $this->ratingItem = $ratingItem;
    }

    public function handle(): void
    {
        DeleteRatingItemsAction::refreshRatingItems($this->ratingItem);
    }
}
