<?php namespace App\Repositories\OrderTicket;

use App\Models\Order\Order;
use App\Models\Order\OrderPromotion;
use App\Services\Order\OrderProtocol;

interface OrderTicketRepositoryContract {

    public function createOrderTicket(Order $order);

    public function getOrderTicketsOfOrder($order_id);

    public function getOrderTicketsOfUser($user_id, $status = OrderProtocol::ORDER_TICKET_STATUS_OF_OK, $per_page = OrderTicketProtocol::TICKET_PER_PAGE);

    public function getOrderTicketsOfStore($store_id, $start_at = null, $end_at = null, $per_page = OrderTicketProtocol::TICKET_PER_PAGE);

    public function getOrderTicket($ticket_no, $with_detail = true);

    public function updateOrderStatusAsUsed($ticket_no, $store_id);

    public function updateOrderStatus($ticket_ids, $status);


}
