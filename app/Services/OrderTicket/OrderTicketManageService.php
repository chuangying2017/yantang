<?php namespace App\Services\OrderTicket;

use App\Repositories\Order\ClientOrderRepositoryContract;
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
     * OrderTicketManageService constructor.
     * @param OrderTicketRepositoryContract $ticketRepo
     */
    public function __construct(OrderTicketRepositoryContract $ticketRepo, ClientOrderRepositoryContract $orderRepo)
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

        return true;
    }


}
