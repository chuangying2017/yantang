<?php namespace App\Repositories\Promotion\Coupon;

use App\Models\Promotion\Coupon;
use App\Models\Promotion\Ticket;
use App\Repositories\Promotion\Traits\Counter;
use App\Services\Promotion\PromotionProtocol;

class EloquentTicketRepository implements TicketRepositoryContract {

    use Counter;

    public function createTicket($user_id = null, Coupon $coupon, $generate_no = false)
    {
        return Ticket::create([
            'user_id' => $user_id,
            'coupon_id' => $coupon['id'],
            'ticket_no' => $generate_no ? '' : str_random(PromotionProtocol::LENGTH_OF_TICKET_NO),
            'start_time' => $coupon['start_time'],
            'end_time' => $this->calEndTime($coupon),
            'status' => PromotionProtocol::STATUS_OF_TICKET_OK
        ]);
    }

    private function calEndTime(Coupon $coupon)
    {
        $days = $this->getCounter('effect_days');
        if ($days > 0) {
            return $this->getBiggerTime(time() + $days * 24 * 3600, strtotime($coupon['end_time']));
        }
        return $coupon['end_time'];
    }

    protected function getBiggerTime($left, $right)
    {
        return date('Y-m-d H:i:s', $left > $right ? $left : $right);
    }

    public function updateTicket($ticket_id, $status)
    {
        $ticket = $this->getTicket($ticket_id);

        if ($ticket !== PromotionProtocol::STATUS_OF_TICKET_OK) {
            throw new \Exception('当前状态不可改变');
        }

        $ticket->status = $status;
        $ticket->save();
        return $ticket;
    }

    public function deleteTicket($ticket_id)
    {
        return Ticket::where('id', $ticket_id)->delete();
    }

    public function getTicket($ticket_id, $with_coupon = true)
    {
        if ($with_coupon) {
            return Ticket::with('coupon')->find($ticket_id);
        }
        return Ticket::find($ticket_id);
    }

    public function getTicketsByCoupon($coupon_id, $with_coupon = true)
    {
        if ($with_coupon) {
            return Ticket::with('coupon')->where('coupon_id', $coupon_id)->get();
        }
        return Ticket::where('coupon_id', $coupon_id)->get();
    }

    public function getTicketsByUser($user_id, $with_coupon = true)
    {
        if ($with_coupon) {
            return Ticket::with('coupon')->where('user_id', $user_id)->get();
        }
        return Ticket::where('user_id', $user_id)->get();
    }
}
