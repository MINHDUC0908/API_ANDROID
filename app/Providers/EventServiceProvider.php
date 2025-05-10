<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\ProductAdded::class => [
            \App\Listeners\LogProductAdded::class,
        ],
        \App\Events\OrderPlaced::class => [
            \App\Listeners\LogOrderPlaced::class,
        ],
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
