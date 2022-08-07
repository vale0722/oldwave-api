<?php

namespace App\Console\Commands;

use App\Jobs\RefreshRatingProductsJob;
use App\Models\RatingProduct;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ConsultRatingProducts extends Command
{
    protected  $signature = 'command:name';

    protected $description = 'Command description';

    public function handle()
    {
        $yesterday = Carbon::yesterday();
        $ratingProducts = RatingProduct::where('created_at', '<' ,$yesterday );
        foreach ($ratingProducts as $ratingProduct)
        {
            RefreshRatingProductsJob::dispatch($ratingProduct);
        }
    }
}
