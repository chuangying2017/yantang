<?php namespace App\Repositories\OrderTicket;

use App\Models\Order\Order;
use App\Models\OrderTicket;
use App\Repositories\NoGenerator;
use App\Services\Order\OrderProtocol;

class EloquentOrderTicketRepository implements OrderTicketRepositoryContract {

    public function createOrderTicket(Order $order)
    {
        $ticket = $this->getOrderTicketsOfOrder($order['id']);
        if ($ticket) {
            return $ticket;
        }

        return OrderTicket::create([
            'order_id' => $order['id'],
            'user_id' => $order['user_id'],
            'status' => OrderProtocol::ORDER_TICKET_STATUS_OF_OK
        ]);
    }

    public function getOrderTicketsOfUser($user_id, $status = OrderProtocol::ORDER_TICKET_STATUS_OF_OK)
    {
        $query = OrderTicket::where('user_id', $user_id);
        if ($status) {
            $query = $query->where('status', $status);
        }
        return $query->get();
    }

    public function getOrderTicket($ticket_no, $with_detail = false)
    {
        if ($ticket_no instanceof OrderTicket) {
            $ticket = $ticket_no;
        } else if (NoGenerator::isOrderTicketNo($ticket_no)) {
            $ticket = OrderTicket::where('ticket_no', $ticket_no);
        } else {
            $ticket = OrderTicket::findOrFail($ticket_no);
        }


        if ($with_detail) {

        }

        return $ticket;
    }

    public function updateOrderStatusAsUsed($ticket_no, $store_id)
    {
        $ticket = $this->getOrderTicket($ticket_no);
        $ticket->store_id = $store_id;
        $ticket->status = OrderProtocol::ORDER_TICKET_STATUS_OF_USED;
        $ticket->save();
        return $ticket;
    }

    public function updateOrderStatus($tickets, $status)
    {
        return OrderTicket::whereIn(to_array($tickets))->update(['status' => $status]);
    }

    public function getOrderTicketsOfOrder($order_id)
    {
        return OrderTicket::where('order_id', $order_id)->first();
    }
}
