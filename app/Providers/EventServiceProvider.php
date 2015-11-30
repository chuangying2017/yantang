<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Services\Orders\Event\OrderRequest' => [
            'App\Services\Orders\Listeners\OrderRequestListener',
        ],
        'App\Services\Orders\Event\OrderConfirm' => [
            'App\Services\Orders\Listeners\OrderConfirmListener',
            'App\Services\Cart\Listeners\OrderConfirmListener'
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
