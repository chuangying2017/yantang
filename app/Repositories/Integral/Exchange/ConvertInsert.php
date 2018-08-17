<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/16/016
 * Time: 17:14
 */

namespace App\Repositories\Integral\Exchange;


use App\Models\Client\Account\Wallet;
use App\Models\Integral\IntegralRecord;
use App\Models\Promotion\Ticket;
use App\Repositories\Common\updateOrSave\CommonInsertMode;
use App\Repositories\Promotion\Traits\Counter;
use App\Services\Promotion\PromotionProtocol;
use Carbon\Carbon;

abstract class ConvertInsert extends CommonInsertMode
{
    use Counter;

    protected $VerifyData;

    protected $model;

    protected $User;
    public function createTicket()
    {
        $promotion = $this->model->promotions;

        $nowDate = Carbon::now()->toDateTimeString();

        $ticket_data = [
            'user_id' => $this->VerifyData['user_id'],
            'promotion_id' => $promotion['id'],
            'ticket_no' => str_random(PromotionProtocol::LENGTH_OF_TICKET_NO),
            'start_time' => $promotion['start_time'],
            'end_time' => Carbon::now()->addHour($this->model['delayed'])->toDateTimeString(),
            'type' => $promotion['type'],
            'status' => PromotionProtocol::STATUS_OF_TICKET_OK,
            'source_type' => PromotionProtocol::QUALI_TYPE_OF_USER,
            'source_id' => 0,
            'created_at' => $nowDate,
            'updated_at' => $nowDate,
        ];
        Ticket::create($ticket_data);
        $this->increaseCounter($promotion['id'], PromotionProtocol::NAME_OF_COUNTER_DISPATCH, 1);
    }

    public function counter_convert()
    {
        $this->model->increment('draw_num',1);

        $this->model->decrement('remain_num',1);

        $this->User->decrement('integral',$this->model->cost_integral);
    }

    public function integral_record()
    {
       $this-> save(new IntegralRecord(),[
            'type_id' => $this->model->id,
            'record_able' => get_class($this->model),
            'user_id' => $this->VerifyData['user_id'],
            'name' => '积分兑换优惠卷',
            'integral' => '-' . $this->model->cost_integral
            ]);
    }
}