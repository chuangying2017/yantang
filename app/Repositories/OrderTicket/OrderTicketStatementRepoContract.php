<?php namespace App\Repositories\OrderTicket;
interface OrderTicketStatementRepoContract {

    public function getStoreOrderTicketsWithProducts($store_id, $time_before);

    public function updateOrderTicketsAsChecked($order_tickets);

}
