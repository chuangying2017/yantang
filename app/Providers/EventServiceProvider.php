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
            'App\Services\Orders\Listeners\CacheOrderRequestData',
        ],
        'App\Services\Orders\Event\OrderConfirm' => [
            'App\Services\Cart\Listeners\RemovePurchasedItemsFromCart',
            'App\Services\Marketing\Listeners\GenerateMarketingBillingAndFrozenMarketingItem',
        ],
        'App\Services\Orders\Event\PingxxPaid'   => [
            'App\Services\Orders\Listeners\HandlePingxxBilling',
            'App\Services\Orders\Listeners\HandleOrderPaid',
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
