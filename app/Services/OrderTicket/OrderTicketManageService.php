<?php namespace App\Services\OrderTicket;

use App\Repositories\Order\ClientOrderRepositoryContract;
use App\Repositories\Order\Promotion\OrderPromotionContract;
use App\Repositories\OrderTicket\OrderTicketRepositoryContract;
use App\Services\Order\OrderProtocol;

class OrderTicketManageService implements OrderTicketManageContract {

    /**
     * @var OrderTicketRepositoryContract
     */
    private $ticketRepo;
    /**
     * @var ClientOrderRepositoryContract
     */
    private $orderRepo;
    /**
     * @var OrderPromotionContract
     */
    private $orderPromotion;

    /**
     * OrderTicketManageService constructor.
     * @param OrderTicketRepositoryContract $ticketRepo
     */
    public function __construct(
        OrderTicketRepositoryContract $ticketRepo,
        ClientOrderRepositoryContract $orderRepo,
        OrderPromotionContract $orderPromotion
    )
    {
        $this->ticketRepo = $ticketRepo;
        $this->orderRepo = $orderRepo;
        $this->orderPromotion = $orderPromotion;
    }

    public function createTicket($order_id)
    {
        $order = $this->orderRepo->getOrder($order_id);

        if ($order['status'] !== OrderProtocol::STATUS_OF_PAID) {
            throw new \Exception('订单状态错误,无法生成兑换券');
        }

        $order_promotions = $this->orderPromotion->getOrderPromotion($order['id']);
        $this->ticketRepo->createOrderTicket($order, $order_promotions->first());
        $this->orderRepo->updateOrderStatusAsDeliver($order);

        return true;
    }


}
