<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderIsPaid;
use App\Services\Order\OrderProtocol;
use App\Services\OrderTicket\OrderTicketManageContract;
use App\Services\Order\OrderManageContract;
use App\Services\Promotion\PromotionProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateOrderTicketForCampaignOrder {

    /**
     * @var OrderTicketManageContract
     */
    private $orderTicketManage;

    /**
     * @var OrderRepositoryContract
     */
    private $orderManage;

    /**
     * @var CouponService
     */
    private $couponService;

    /**
     * @var CouponRepositoryContract
     */
    private $couponRepo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OrderTicketManageContract $orderTicketManage,
       OrderManageContract $orderManage, CouponService $couponService, CouponRepositoryContract $couponRepo)
    {
        $this->orderTicketManage = $orderTicketManage;
        $this->orderManage = $orderManage;
        $this->couponService = $couponService;
        $this->couponRepo = $couponRepo;
    }

    /**
     * Handle the event.
     *
     * @param  OrderIsPaid $event
     * @return void
     */
    public function handle(OrderIsPaid $event)
    {
        $order = $event->order;
        if ($order['order_type'] == OrderProtocol::ORDER_TYPE_OF_CAMPAIGN) {
            $this->orderTicketManage->createTicket($order);
        }
        else if( $order['order_type'] == OrderProtocol::ORDER_TYPE_OF_COLLECT) {
            $this->orderManage->orderDone($order['id']);

            try{
                //送券
                $coupons = $this->couponRepo->getAllByQualifyTye(PromotionProtocol::QUALI_TYPE_OF_COLLECT_ORDER);
                if (count($coupons)) {
                    foreach ($coupons as $coupon) {
                        $result = $this->couponService->dispatchWithoutCheck($user['id'], $coupon['id']);
                    }
                }
            }
            catch( \Exception $e ){
                \Log::error( 'Failed to dispatch ticket when collect order has finished. '.$e );
            }
        }
    }
}
