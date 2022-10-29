<?php

namespace App\Providers;

use App\Services\PaymentServiceContract;
use App\Services\PlacetopayServiceContract;
use Dnetix\Redirection\PlacetoPay;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(PaymentServiceContract::class, function ($app) {
            $service = config('services.payment_service');
            $settings = config('services.payment_services.' . $service);
            $classServices = $settings['class'];

            return new $classServices($settings);
        });

        $this->app->bind(PlacetopayServiceContract::class, function ($app, $settings) {
            return new PlacetoPay($settings);
        });
    }
}
