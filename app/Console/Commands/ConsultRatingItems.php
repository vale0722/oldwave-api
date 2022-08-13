<?php

namespace App\Console\Commands;

use App\Jobs\RefreshRatingItemsJob;
use App\Models\RatingItem;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ConsultRatingItems extends Command
{
    protected $signature = 'rating:items';

    protected $description = 'Command description';

    public function handle()
    {
        $yesterday = Carbon::yesterday();
        $ratingItems = RatingItem::where('created_at', '<', $yesterday);
        foreach ($ratingItems as $ratingItem) {
            RefreshRatingItemsJob::dispatch($ratingItem);
        }
    }
}
