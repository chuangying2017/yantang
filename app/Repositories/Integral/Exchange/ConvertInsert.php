<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/16/016
 * Time: 17:14
 */

namespace App\Repositories\Integral\Exchange;


use App\Models\Promotion\Ticket;
use App\Repositories\Promotion\Traits\Counter;
use App\Services\Promotion\PromotionProtocol;
use Carbon\Carbon;

abstract class ConvertInsert
{
    use Counter;

    protected $VerifyData;

    protected $model;

    public function createTicket()
    {
        $promotion = $this->model->promotions;
        $ticket_data = [
            'user_id' => $this->VerifyData['user_id'],
            'promotion_id' => $promotion['id'],
            'ticket_no' => str_random(PromotionProtocol::LENGTH_OF_TICKET_NO),
            'start_time' => $promotion['start_time'],
            'end_time' => date('Y-m-d H:i:s',time() + 3600 * !empty($this->model['delayed']) ? $this->model['delayed'] : 1),
            'type' => $promotion['type'],
            'status' => PromotionProtocol::STATUS_OF_TICKET_OK,
            'source_type' => PromotionProtocol::QUALI_TYPE_OF_USER,
            'source_id' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        Ticket::create($ticket_data);
        $this->increaseCounter($promotion['id'], PromotionProtocol::NAME_OF_COUNTER_DISPATCH, 1);
    }
}