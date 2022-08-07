<?php

namespace App\Jobs;

use App\Actions\DeleteRatingProductAction;
use App\Models\RatingProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefreshRatingProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected RatingProduct $ratingProduct;

    public function __construct(RatingProduct $ratingProduct)
    {
        $this->ratingProduct = $ratingProduct;
    }

    public function handle(): void
    {
        DeleteRatingProductAction::refreshRatingProducts($this->ratingProduct);
    }
}
