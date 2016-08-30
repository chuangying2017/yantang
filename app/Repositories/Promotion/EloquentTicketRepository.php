<?php namespace App\Repositories\Promotion;

use App\Models\Promotion\PromotionAbstract;
use App\Models\Promotion\Ticket;
use App\Repositories\Promotion\Traits\Counter;
use App\Services\Promotion\PromotionProtocol;
use Carbon\Carbon;

class EloquentTicketRepository implements TicketRepositoryContract {

    use Counter;

    public function createTicket($user_id = null, PromotionAbstract $promotion, $generate_no = false)
    {
        $ticket_data = [
            'promotion_id' => $promotion['id'],
            'ticket_no' => $generate_no ? str_random(PromotionProtocol::LENGTH_OF_TICKET_NO) : '',
            'start_time' => $promotion['start_time'],
            'end_time' => $this->calEndTime($promotion),
            'type' => $promotion['type'],
            'status' => PromotionProtocol::STATUS_OF_TICKET_OK,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        if (!is_array($user_id)) {
            $ticket_data['user_id'] = $user_id;
            $result = Ticket::create($ticket_data);
        } else {
            if (empty($user_id)) {
                return 0;
            }
            $tickets_data = [];
            foreach ($user_id as $dispatch_user_id) {
                $ticket_data['user_id'] = $dispatch_user_id;
                $tickets_data[] = $ticket_data;
            }
            if (!count($tickets_data)) {
                return false;
            }
            $result = Ticket::insert($tickets_data);
            return count($tickets_data);
        }

        $ticket_count = is_null($user_id) ? 1 : count($user_id);
        $this->increaseCounter($promotion['id'], PromotionProtocol::NAME_OF_COUNTER_DISPATCH, $ticket_count);

        return $result;
    }

    protected function updateTicket($ticket_id, $status, $rule_id = 0)
    {
        $ticket = $this->getTicket($ticket_id);
        if (!is_null($rule_id)) {
            $ticket->rule_id = $rule_id;
        }
        $ticket->status = $status;
        $ticket->save();
        return $ticket;
    }

    public function updateAsUsed($ticket_id, $rule_id = 0)
    {
        $ticket = $this->getTicket($ticket_id);
        if ($ticket['status'] === PromotionProtocol::STATUS_OF_TICKET_OK) {
            $this->increaseCounter($ticket['promotion_id'], PromotionProtocol::NAME_OF_COUNTER_USED);
            return $this->updateTicket($ticket, PromotionProtocol::STATUS_OF_TICKET_USED, $rule_id);
        }

        return $ticket;
    }

    public function updateAsOk($ticket_id)
    {
        $ticket = $this->getTicket($ticket_id);

        if ($ticket['status'] == PromotionProtocol::STATUS_OF_TICKET_USED) {
            $this->decreaseCounter($ticket['promotion_id'], PromotionProtocol::NAME_OF_COUNTER_USED);
            return $this->updateTicket($ticket_id, PromotionProtocol::STATUS_OF_TICKET_OK, 0);
        }

        return $ticket;
    }

    public function updateAsExpire($ticket_id)
    {
        $ticket = $this->getTicket($ticket_id);

        if ($ticket['status'] == PromotionProtocol::STATUS_OF_TICKET_OK) {
            return $this->updateTicket($ticket_id, PromotionProtocol::STATUS_OF_TICKET_EXPIRED, 0);
        }

        return $ticket;
    }

    public function deleteTicket($ticket_id)
    {
        $ticket = $this->getTicket($ticket_id, false);
        $this->decreaseCounter($ticket['promotion_id'], PromotionProtocol::NAME_OF_COUNTER_DISPATCH);
        if ($ticket['status'] == PromotionProtocol::STATUS_OF_TICKET_USED) {
            $this->decreaseCounter($ticket['promotion_id'], PromotionProtocol::NAME_OF_COUNTER_USED);
        }

        return Ticket::query()->where('id', $ticket_id)->delete();
    }

    /**
     * @param $ticket_id
     * @param bool $with_promotion
     * @return Ticket
     */
    public function getTicket($ticket_id, $with_promotion = true)
    {
        if ($ticket_id instanceof Ticket) {
            $ticket = $ticket_id;
        } else if (strlen($ticket_id) == PromotionProtocol::LENGTH_OF_TICKET_NO) {
            $ticket = Ticket::query()->where('ticket_no', $ticket_id)->firstOrFail();
        } else {
            $ticket = Ticket::query()->findOrFail($ticket_id);
        }

        if ($with_promotion) {
            $ticket->load('coupon');
        }

        if ($ticket['end_time'] < Carbon::now() && $ticket['status'] == PromotionProtocol::STATUS_OF_TICKET_OK) {
            $ticket = $this->updateAsExpire($ticket);
        }

        return $ticket;
    }

    public function getCouponTicketsOfUser($user_id, $status, $with_coupon = true)
    {
        return $this->query(PromotionProtocol::TYPE_OF_COUPON, $status, $user_id, null, $with_coupon, null);
    }

    public function getCouponTicketsOfUserPaginated($user_id, $status, $with_coupon = true)
    {
        return $this->query(PromotionProtocol::TYPE_OF_COUPON, $status, $user_id, null, $with_coupon);
    }

    protected function query($type, $status = PromotionProtocol::STATUS_OF_TICKET_OK, $user_id = null, $promotion_id = null, $with_promotion = true, $paginate = PromotionProtocol::TICKET_PER_PAGE)
    {
        $query = Ticket::query()->where('type', $type);

        if ($with_promotion) {
            $query->with('coupon');
        }

        if ($promotion_id) {
            $query->where('promotion_id', $promotion_id);
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

    private function calEndTime(PromotionAbstract $coupon)
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

    public static function getUserPromotionTimes($promotion_id, $user_id, $rule_id = null)
    {
        $query = Ticket::query()
            ->where('user_id', $user_id)
            ->where('promotion_id', $promotion_id)
            ->where('status', '!=', PromotionProtocol::STATUS_OF_TICKET_CANCEL);

        if (!is_null($rule_id)) {
            $query = $query->where('rule_id', $rule_id);
        }

        return $query->get()->count();
    }

    public function createLogTicket($user_id, $promotion_id, $promotion_type, $rule_id)
    {
        $ticket = Ticket::create([
            'user_id' => $user_id,
            'promotion_id' => $promotion_id,
            'ticket_no' => $promotion_id . str_random(PromotionProtocol::LENGTH_OF_TICKET_NO),
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addDay(10),
            'type' => $promotion_type,
            'status' => PromotionProtocol::STATUS_OF_TICKET_USED
        ]);

        $this->increaseCounter($promotion_id, PromotionProtocol::NAME_OF_COUNTER_DISPATCH);

        $this->updateAsUsed($ticket, $rule_id);

        return $ticket;
    }


    public function updateAsCancel($ticket_id)
    {
        $ticket = $this->getTicket($ticket_id);

        if ($ticket['status'] == PromotionProtocol::STATUS_OF_TICKET_USED) {
            $this->decreaseCounter($ticket['promotion_id'], PromotionProtocol::NAME_OF_COUNTER_USED);
            return $this->updateTicket($ticket_id, PromotionProtocol::STATUS_OF_TICKET_CANCEL, null);
        }

        return $ticket;
    }
}
