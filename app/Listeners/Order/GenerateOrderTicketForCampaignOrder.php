<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderIsPaid;
use App\Services\Order\OrderProtocol;
use App\Services\OrderTicket\OrderTicketManageContract;
use App\Services\Order\OrderManageContract;
use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\CouponService;
use App\Repositories\Promotion\TicketRepositoryContract;
use App\Repositories\Promotion\Coupon\CouponRepositoryContract;
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
     * @var TicketRepositoryContract
     */
    private $ticketRepo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OrderTicketManageContract $orderTicketManage,
       OrderManageContract $orderManage, CouponService $couponService, CouponRepositoryContract $couponRepo, TicketRepositoryContract $ticketRepo)
    {
        $this->orderTicketManage = $orderTicketManage;
        $this->orderManage = $orderManage;
        $this->couponService = $couponService;
        $this->couponRepo = $couponRepo;
        $this->ticketRepo = $ticketRepo;
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
                $coupons = $this->couponRepo->getAllByQualifyTye(PromotionProtocol::QUALI_TYPE_OF_COLLECT_ORDER);
                $userCoupons = $this->ticketRepo->getCouponTicketsOfUser($order['user_id'],PromotionProtocol::STATUS_OF_TICKET_OK, true)->pluck('coupon');

                $collectCouponIds = array_column( $coupons->toArray(), 'id' );
                $userCouponIds = array_column( $userCoupons->toArray(), 'id' );
                $dispatchCouponIds = array_diff( $collectCouponIds, $userCouponIds );

                //check user has unused such coupon
                foreach ($coupons as $coupon) {
                    if( in_array( $coupon->id, $dispatchCouponIds ) ){
                        $result = $this->couponService->dispatchWithoutCheck($order['user_id'], $coupon['id']);
                    }
                }
            }
            catch( \Exception $e ){
                \Log::error( 'Failed to dispatch ticket when collect order has finished. '.$e );
            }
        }
    }
}
