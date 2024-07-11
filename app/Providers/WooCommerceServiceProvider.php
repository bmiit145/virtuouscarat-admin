<?php

namespace App\Providers;

use Automattic\WooCommerce\Client;
use Illuminate\Support\ServiceProvider;

class WooCommerceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            $config = config('woocommerce');
            return new Client(
                $config['url'],
                $config['consumer_key'],
                $config['consumer_secret'],
                $config['options']
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
