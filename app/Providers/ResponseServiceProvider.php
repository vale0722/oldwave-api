<?php

namespace App\Providers;

use App\Responses\ApiGeneralResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('api', function ($status, $data) {
            return (new ApiGeneralResponse($status, $data))->toArray();
        });
    }
}
