<?php namespace App\Services\OrderTicket;
interface OrderTicketManageContract {

    public function createTicket($order_id);

    public function exchange($ticket_no, $store_id);

}
