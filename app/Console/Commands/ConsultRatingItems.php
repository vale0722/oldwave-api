<?php

namespace App\Console\Commands;

use App\Models\RatingItem;
use Illuminate\Console\Command;

class ConsultRatingItems extends Command
{
    protected $signature = 'rating:items';

    protected $description = 'Command description';

    public function handle()
    {
        $ratingItems = RatingItem::where('created_at', '<', now()->subDay())->get();

        foreach ($ratingItems as $ratingItem) {
            $ratingItem->delete();
        }
    }
}
