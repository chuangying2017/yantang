<?php namespace App\Repositories\Promotion\Coupon;

use App\Models\Promotion\Coupon;
use App\Models\Promotion\Ticket;
use App\Repositories\Promotion\PromotionSupportRepository;
use App\Repositories\Promotion\Traits\Counter;
use App\Services\Promotion\PromotionProtocol;

class EloquentTicketRepository implements TicketRepositoryContract {

    use Counter;

    public function createTicket($user_id = null, Coupon $coupon, $generate_no = false)
    {
        $ticket = Ticket::create([
            'user_id' => $user_id,
            'promotion_id' => $coupon['id'],
            'ticket_no' => $generate_no ? str_random(PromotionProtocol::LENGTH_OF_TICKET_NO) : '',
            'start_time' => $coupon['start_time'],
            'end_time' => $this->calEndTime($coupon),
            'status' => PromotionProtocol::STATUS_OF_TICKET_OK
        ]);

        $user_promotion = PromotionSupportRepository::addUserPromotion($user_id, $coupon['id']);

        return $ticket;
    }

    private function calEndTime(Coupon $coupon)
    {
        $days = $this->getCounter($coupon['id'], 'effect_days');

        if ($days > 0) {
            return $this->getEarlyTime(time() + $days * 24 * 3600, strtotime($coupon['end_time']));
        }
        return $coupon['end_time'];
    }

    protected function getBiggerTime($left, $right)
    {
        return date('Y-m-d H:i:s', $left > $right ? $left : $right);
    }

    protected function getEarlyTime($left, $right)
    {
        return date('Y-m-d H:i:s', $left < $right ? $left : $right);
    }

    public function updateTicket($ticket_id, $status)
    {
        $ticket = $this->getTicket($ticket_id);
        
        if ($ticket['status'] !== PromotionProtocol::STATUS_OF_TICKET_OK) {
            throw new \Exception('当前状态不可改变');
        }

        $ticket->status = $status;
        $ticket->save();
        return $ticket;
    }

    public function updateAsUsed($ticket_id)
    {
        return $this->updateTicket($ticket_id, PromotionProtocol::STATUS_OF_TICKET_USED);
    }

    public function deleteTicket($ticket_id)
    {
        return Ticket::query()->where('id', $ticket_id)->delete();
    }

    /**
     * @param $ticket_id
     * @param bool $with_coupon
     * @return Ticket
     */
    public function getTicket($ticket_id, $with_coupon = true)
    {
        if ($with_coupon) {
            return Ticket::with('coupon')->find($ticket_id);
        }
        return Ticket::query()->find($ticket_id);
    }

    public function getTicketsByUser($user_id, $status, $with_coupon = true)
    {
        return $this->query($status, $user_id, null, $with_coupon);
    }

    protected function query($status = PromotionProtocol::STATUS_OF_TICKET_OK, $user_id = null, $coupon_id = null, $with_coupon = true, $paginate = PromotionProtocol::TICKET_PER_PAGE)
    {
        $query = Ticket::query();

        if ($with_coupon) {
            $query->with('coupon');
        }

        if ($coupon_id) {
            $query->where('promotion_id', $coupon_id);
        }

        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        if ($status == PromotionProtocol::STATUS_OF_TICKET_OK) {
            $query->effect();
        }

        $query->where('status', $status)->orderBy('created_at', 'desc');


        if ($paginate) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }


}
