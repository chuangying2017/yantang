<?php namespace App\Providers;


use App\Models\AgentRate;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 * @package App\Providers
 */
class EventServiceProvider extends ServiceProvider {

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        'SocialiteProviders\Manager\SocialiteWasCalled' => [
            // add your listeners (aka providers) here
//            'App\Services\Socialite\Weixin\WeixinExtendSocialite',
            'App\Services\Socialite\Meitianhui\MeitianhuiExtendSocialite',
        ],

        /**
         * Frontend Events
         */
        'App\Events\Frontend\Auth\UserLoggedIn'         => [
            'App\Listeners\Frontend\Auth\UserLoggedInHandler',
        ],
        'App\Events\Frontend\Auth\UserLoggedOut'        => [
            'App\Listeners\Frontend\Auth\UserLoggedOutHandler',
        ],

        'App\Events\Frontend\Auth\UserRegister'     => [
            'App\Listeners\Frontend\Auth\CreateClientForUser',
        ],
        'App\Services\Orders\Event\OrderRequest'    => [

        ],
        'App\Services\Orders\Event\OrderInfoChange' => [
            'App\Services\Orders\Listeners\CacheOrderRequestData',
        ],
        'App\Services\Orders\Event\OrderConfirm'    => [
            'App\Services\Cart\Listeners\RemovePurchasedItemsFromCart',
            'App\Services\Marketing\Listeners\GenerateMarketingBillingAndFrozenMarketingItem',
            'App\Services\Marketing\Listeners\GenerateMainBilling',
            'App\Services\Product\Listeners\DecreaseStock',
            'App\Services\Orders\Listeners\OrderSpilt',
        ],
        'App\Services\Orders\Event\OrderCancel'     => [
            'App\Services\Marketing\Listeners\DeleteMarketingBillingAndUnFrozenMarketingItem',
            'App\Services\Product\Listeners\IncreaseStock',
            'App\Services\Orders\Listeners\DeleteOrderPayment',
        ],
        'App\Services\Orders\Event\PingxxPaid'      => [
            'App\Services\Orders\Listeners\HandlePingxxBilling',
        ],
        'App\Services\Orders\Event\OrderIsPaid'     => [
            'App\Services\Orders\Listeners\HandleOrderPaid',
            'App\Services\Marketing\Listeners\UpdateMarketingBillingAndUsedMarketingItem',
            'App\Services\Agent\Listeners\AgentOrderDeal',
            'App\Services\Merchant\Listeners\SendOrderInfoToMerchant',
        ],

        'App\Services\Orders\Event\OrderDone' => [

        ],

        'App\Services\Orders\Event\OrderRefundApply' => [
            'App\Services\Merchant\Listeners\SendOrderRefundNotifyToMerchant',
        ],

        'App\Services\Orders\Event\OrderRefundApprove' => [
            'App\Services\Agent\Listeners\CancelAwardAgent',
        ],

        'App\Services\Orders\Event\OrderRefundDeliver' => [

        ],

        'App\Services\Orders\Event\OrderRefunding' => [
            'App\Services\Clients\Listeners\SendOrderRefundApproveNotifyToClient',
        ],

        'App\Services\Orders\Event\OrderRefunded' => [

        ],


        /**
         * 九级分销
         */
        'App\Services\Agent\Event\NewAgentOrder'  => [
            'App\Services\Agent\Listeners\AwardClientAgent',
            'App\Services\Agent\Listeners\AwardAgent',
        ],

        'App\Services\Agent\Event\NewAgent' => [
            'App\Services\Agent\Listeners\CreateNewAgent',
            'App\Services\Agent\Listeners\SendNotifyToAgent',
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

