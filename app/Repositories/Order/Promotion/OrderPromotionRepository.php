<?php namespace App\Repositories\Order\Promotion;

use App\Models\Order\OrderPromotion;
use App\Repositories\Promotion\TicketRepositoryContract;
use App\Services\Order\OrderProtocol;

class OrderPromotionRepository implements OrderPromotionRepositoryContract {

    /**
     * OrderPromotionRepository constructor.
     * @param TicketRepositoryContract $ticketRepo
     */
    public function __construct(TicketRepositoryContract $ticketRepo)
    {
        $this->ticketRepo = $ticketRepo;
    }

    public function createOrderPromotion($order_id, $promotions_data)
    {
        if (!$promotions_data) {
            return;
        }

        foreach ($promotions_data as $promotion_data) {

            if (isset($promotion_data['ticket'])) {
                $ticket = $this->ticketRepo->updateAsUsed($promotion_data['ticket']['id'], $promotion_data['id']);
            } else {
                $ticket = $this->ticketRepo->createLogTicket(access()->id(), $promotion_data['promotion_id'], $promotion_data['promotion']['type'], $promotion_data['id']);
            }

            OrderPromotion::create([
                'order_id' => $order_id,
                'promotion_type' => $promotion_data['promotion_type'],
                'promotion_id' => $promotion_data['promotion_id'],
                'promotion_rule_id' => $promotion_data['id'],
                'ticket_id' => $ticket['id'],
                'content' => json_encode($promotion_data['benefit'])
            ]);

        }
    }

    public function getOrderPromotion($order_id)
    {
        return OrderPromotion::query()->where('order_id', $order_id)->get();
    }

    public function updateOrderPromotionFinish($order_promotion_id)
    {
        $order_promotion = OrderPromotion::query()->find($order_promotion_id);
        $order_promotion->status = OrderProtocol::ORDER_PROMOTION_STATUS_OF_DONE;
        $order_promotion->save();
        return $order_promotion;
    }

    /**
     * @var TicketRepositoryContract
     */
    private $ticketRepo;
}
