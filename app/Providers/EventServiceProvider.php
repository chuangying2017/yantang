<?php namespace App\Providers;


use App\Models\Product\Product;
use App\Services\Product\Listeners\ProductSearchObserver;
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

        //第三方授权注册
        'SocialiteProviders\Manager\SocialiteWasCalled' => [
            'App\Services\Socialite\Weixin\WeixinExtendSocialite',
        ],

        /**
         * Frontend Events
         */
        'App\Events\Auth\UserLoggedIn' => [
            'App\Listeners\Auth\UserLoggedInHandler',
        ],

        'App\Events\Auth\UserRegister' => [
            'App\Listeners\Auth\CreateClientForUser',
            'App\Listeners\Auth\CreateWalletForUser',
            'App\Listeners\Auth\CreateCreditsWalletForUser'
        ],

        'App\Events\Auth\UserLoggedOut' => [
            'App\Listeners\Auth\UserLoggedOutHandler',
        ],

        'App\Services\Pay\Events\PingxxPaymentIsPaid' => [
            'App\Listeners\Order\SetOrderMainBillingAsPaid',
            'App\Listeners\Subscribe\SetChargeBillingAsPaid',
        ],

        'App\Events\Order\MainBillingIsPaid' => [
            'App\Listeners\Order\SetOrderAsPaid',
        ],

        'App\Events\Order\OrderIsPaid' => [
            'App\Listeners\Order\GenerateOrderTicketForCampaignOrder',
            'App\Listeners\Product\DecreaseProductSkuStock',
        ],

        'App\Events\Order\OrderIsCreated' => [
            'App\Listeners\Order\RemoveCheckoutCartItems',
        ],

        'App\Events\Order\OrderIsCancel' => [
            'App\Listeners\Product\IncreaseProductSkuStock',
        ],

        'App\Events\Order\OrderIsDone' => [

        ],

        'App\Events\Order\OrderIsDeliver' => [

        ],

        'App\Events\Order\OrderIsDeliverDone' => [

        ],

        'App\Events\Order\OrderIsRefund' => [

        ],

        'App\Events\Order\OrderTicketIsExchange' => [

        ],

        /**
         * 对账
         */
        'App\Events\Statement\StatementConfirm' => [

        ],

        'App\Events\Statement\StatementError' => [

        ],

        /**
         * 订奶订单
         */

        'App\Events\Preorder\ChargeBillingIsPaid' => [
            'App\Listeners\Preorder\RechargeWallet',
            'App\Listeners\Preorder\SetPreorderAsCharged',
        ],

        'App\Events\Preorder\PaidPreorderBillingFail' => [
            'App\Listeners\Preorder\SetPreorderAsOut',
        ],

        'App\Events\Preorder\PreorderIsCancel' => [
        ],

        /**
         * 订奶订单分配
         */
        'App\Events\Preorder\AssignIsCreate' => [

        ],

        'App\Events\Preorder\AssignIsConfirm' => [
            'App\Listeners\Preorder\SetPreorderAsPending'
        ],

        'App\Events\Preorder\StaffAssignIsCreate' => [
            'App\Listeners\Preorder\IncreaseStaffWeeklySkus'
        ],

        'App\Events\Preorder\AssignIsReject' => [
            'App\Listeners\Preorder\RemoveStationFromPreorder'
        ],


//        'App\Services\Orders\Event\OrderRequest' => [
//
//        ],
//        'App\Services\Orders\Event\OrderInfoChange' => [
//            'App\Services\Orders\Listeners\CacheOrderRequestData',
//        ],

//        'App\Services\Orders\Event\OrderConfirm' => [
//            'App\Services\Cart\Listeners\RemovePurchasedItemsFromCart',
//            'App\Services\Marketing\Listeners\GenerateMarketingBillingAndFrozenMarketingItem',
//            'App\Services\Marketing\Listeners\GenerateMainBilling',
//            'App\Services\Product\Listeners\DecreaseStock',
//            'App\Services\Orders\Listeners\OrderSpilt',
//        ],
//        'App\Services\Orders\Event\OrderCancel' => [
//            'App\Services\Marketing\Listeners\DeleteMarketingBillingAndUnFrozenMarketingItem',
//            'App\Services\Product\Listeners\IncreaseStock',
//            'App\Services\Orders\Listeners\DeleteOrderPayment',
//        ],
//        'App\Services\Orders\Event\PingxxPaid' => [
//            'App\Services\Orders\Listeners\HandlePingxxBilling',
//        ],
//        'App\Services\Orders\Event\OrderIsPaid' => [
//            'App\Services\Orders\Listeners\HandleOrderPaid',
//            'App\Services\Marketing\Listeners\UpdateMarketingBillingAndUsedMarketingItem',
//            'App\Services\Agent\Listeners\AgentOrderDeal',
//            'App\Services\Merchant\Listeners\SendOrderInfoToMerchant',
//        ],
//
//        'App\Services\Orders\Event\OrderDone' => [
//
//        ],
//
//        'App\Services\Orders\Event\OrderRefundApply' => [
//            'App\Services\Merchant\Listeners\SendOrderRefundNotifyToMerchant',
//        ],
//
//        'App\Services\Orders\Event\OrderRefundApprove' => [
//            'App\Services\Agent\Listeners\CancelAwardAgent',
//        ],
//
//        'App\Services\Orders\Event\OrderRefundDeliver' => [
//
//        ],
//
//        'App\Services\Orders\Event\OrderRefunding' => [
//            'App\Services\Clients\Listeners\SendOrderRefundApproveNotifyToClient',
//        ],
//
//        'App\Services\Orders\Event\OrderRefunded' => [
//
//        ],


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
    }
}

