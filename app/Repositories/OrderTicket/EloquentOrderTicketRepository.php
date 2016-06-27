<?php namespace App\Repositories\OrderTicket;

use App\Models\Order\Order;
use App\Models\Order\OrderPromotion;
use App\Models\OrderTicket;
use App\Repositories\NoGenerator;
use App\Repositories\Product\ProductProtocol;
use App\Repositories\Statement\StatementAbleBillingRepoContract;
use App\Services\Order\OrderProtocol;
use App\Services\Statement\StatementProtocol;
use Carbon\Carbon;

class EloquentOrderTicketRepository implements OrderTicketRepositoryContract, StatementAbleBillingRepoContract {

    public function createOrderTicket(Order $order)
    {
        $ticket = $this->getOrderTicketsOfOrder($order['id']);
        if ($ticket) {
            return $ticket;
        }

        return OrderTicket::create([
            'order_id' => $order['id'],
            'user_id' => $order['user_id'],
            'ticket_no' => NoGenerator::generateOrderTicketNo($order['order_no']),
            'status' => OrderProtocol::ORDER_TICKET_STATUS_OF_OK
        ]);
    }


    public function getOrderTicketsOfUser($user_id, $status = OrderProtocol::ORDER_TICKET_STATUS_OF_OK, $per_page = OrderTicketProtocol::TICKET_PER_PAGE)
    {
        $query = OrderTicket::query()->with('campaign')->where('user_id', $user_id);

        if ($status) {
            $query = $query->where('status', $status);
        }

        if ($per_page) {
            return $query->paginate(OrderTicketProtocol::TICKET_PER_PAGE);
        }

        return $query->get();
    }

    public function getOrderTicket($ticket_no, $with_detail = false, $with_mix = false)
    {
        if ($ticket_no instanceof OrderTicket) {
            $ticket = $ticket_no;
        } else if (NoGenerator::isOrderTicketNo($ticket_no)) {
            $ticket = OrderTicket::query()->where('ticket_no', $ticket_no)->firstOrFail();
        } else {
            $ticket = OrderTicket::query()->findOrFail($ticket_no);
        }

        if ($with_detail) {
            if ($with_mix) {
                $ticket->load(['skus', 'exchange', 'campaign']);
            }
            $ticket->load(['skus' => function ($query) {
                $query->where('type', '!=', ProductProtocol::TYPE_OF_MIX);
            }, 'exchange', 'campaign']);
        }


        return $ticket;
    }

    public function updateOrderStatusAsUsed($ticket_no, $store_id)
    {
        $ticket = $this->getOrderTicket($ticket_no);
        $ticket->store_id = $store_id;
        $ticket->status = OrderProtocol::ORDER_TICKET_STATUS_OF_USED;
        $ticket->exchange_at = Carbon::now();
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

    public function getOrderTicketsOfStore($store_id, $start_at = null, $end_at = null, $per_page = OrderTicketProtocol::TICKET_PER_PAGE)
    {
        $query = OrderTicket::query()->with(['campaign', 'skus' => function ($query) {
            $query->where('type', '!=', ProductProtocol::TYPE_OF_MIX);
        }])->where('store_id', $store_id);
        if (!is_null($start_at)) {
            $query = $query->where('exchange_at', '>=', $start_at);
        }

        if (!is_null($end_at)) {
            $query = $query->where('exchange_at', '<=', $start_at);
        }

        if ($per_page) {
            return $query->paginate(OrderTicketProtocol::TICKET_PER_PAGE);
        }

        return $query->get();
    }

    public function getBillingWithProducts($store_id, $time_before)
    {
        return OrderTicket::query()->with(['skus' => function ($query) {
            $query->where('type', '!=', ProductProtocol::TYPE_OF_MIX)->select(['id', 'order_id', 'price', 'quantity', 'product_id', 'product_sku_id' , 'name', 'cover_image']);
        }])->where('status', OrderTicketProtocol::STATUS_OF_USED)
            ->where('check', StatementProtocol::CHECK_STATUS_OF_PENDING)
            ->where('exchange_at', '<=', $time_before)
            ->get(['id', 'order_id', 'store_id']);
    }

    public function updateBillingAsCheckout($order_ticket_ids)
    {
        return OrderTicket::whereIn('id', $order_ticket_ids)->update(['checkout' => StatementProtocol::CHECK_STATUS_OF_HANDLED]);
    }
}
