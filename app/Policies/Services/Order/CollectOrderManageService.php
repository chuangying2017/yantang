<?php namespace App\Services\OrderTicket;

use App\Events\Order\OrderTicketIsExchange;
use App\Repositories\Order\CampaignOrderRepository;
use App\Repositories\Order\Promotion\OrderPromotionRepositoryContract;
use App\Repositories\OrderTicket\OrderTicketProtocol;
use App\Repositories\OrderTicket\OrderTicketRepositoryContract;
use App\Services\Order\OrderProtocol;

class CollectOrderManageService implements OrderTicketManageContract {

    /**
     * @var OrderTicketRepositoryContract
     */
    private $ticketRepo;
    /**
     * @var CampaignOrderRepository
     */
    private $orderRepo;


    /**
     * OrderTicketManageService constructor.
     * @param OrderTicketRepositoryContract $ticketRepo
     * @param CampaignOrderRepository $orderRepo
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

        $this->orderRepo->updateOrderStatusAsDeliver($order);
        $this->orderRepo->updateOrderStatusAsDeliverDone($order);

        return true;
    }

    public function exchange($ticket_no, $store_id)
    {

    }

    protected function checkTicket($order_ticket)
    {

    }

}
