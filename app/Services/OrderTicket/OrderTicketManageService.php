<?php namespace App\Services\OrderTicket;

use App\Events\Order\OrderTicketIsExchange;
use App\Repositories\Order\CampaignOrderRepository;
use App\Repositories\Order\Promotion\OrderPromotionRepositoryContract;
use App\Repositories\OrderTicket\OrderTicketProtocol;
use App\Repositories\OrderTicket\OrderTicketRepositoryContract;
use App\Services\Order\OrderProtocol;

class OrderTicketManageService implements OrderTicketManageContract {

    /**
     * @var OrderTicketRepositoryContract
     */
    private $ticketRepo;
    /**
     * @var CampaignOrderRepository
     */
    private $orderRepo;
    /**
     * @var OrderPromotionRepositoryContract
     */
    private $orderPromotion;

    /**
     * OrderTicketManageService constructor.
     * @param OrderTicketRepositoryContract $ticketRepo
     */
    public function __construct(
        OrderTicketRepositoryContract $ticketRepo,
        CampaignOrderRepository $orderRepo
    )
    {
        $this->ticketRepo = $ticketRepo;
        $this->orderRepo = $orderRepo;
    }

    public function createTicket($order_id)
    {
        $order = $this->orderRepo->getOrder($order_id);

        if ($order['status'] !== OrderProtocol::STATUS_OF_PAID) {
            throw new \Exception('订单状态错误,无法生成兑换券');
        }

        $this->ticketRepo->createOrderTicket($order);
        $this->orderRepo->updateOrderStatusAsDeliver($order);
        $this->orderRepo->updateOrderStatusAsDeliverDone($order);

        return true;
    }

    public function exchange($ticket_no, $store_id)
    {
        $order_ticket = $this->ticketRepo->getOrderTicket($ticket_no, true);

        if (!$this->checkTicket($order_ticket)) {
            throw new \Exception('兑换失败,兑换券已失效或过期');
        }

        $order_ticket = $this->ticketRepo->updateOrderStatusAsUsed($ticket_no, $store_id);
        event(new OrderTicketIsExchange($order_ticket));

        return $order_ticket;
    }

    protected function checkTicket($order_ticket)
    {
        return $order_ticket['status'] == OrderTicketProtocol::STATUS_OF_OK;
    }

}
