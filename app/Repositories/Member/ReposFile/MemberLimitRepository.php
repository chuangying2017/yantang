<?php
namespace App\Repositories\Member\ReposFile;

use App\Models\Order\Order;
use App\Models\Settings;
use App\Repositories\Member\InterfaceFile\MemberRepositoryContract;
use App\Services\Order\OrderProtocol;
use Carbon\Carbon;

class MemberLimitRepository implements MemberRepositoryContract
{

    public function new_member($user_id)
    {
        $count = Order::query()->where('user_id','=',$user_id)
            ->whereIn('status',[
                OrderProtocol::REFUND_STATUS_OF_SHIPPED,
                OrderProtocol::STATUS_OF_SHIPPING,
                OrderProtocol::ORDER_PROMOTION_STATUS_OF_DONE,
                OrderProtocol::ORDER_IS_PAID
            ])->where('pay_status',OrderProtocol::ORDER_IS_PAID)
            ->where('refund_status',OrderProtocol::REFUND_STATUS_OF_DEFAULT)
            ->whereBetween('created_at',[Carbon::now()->addMonth(-($this->setDateNumber())),Carbon::now()])
            ->count();

        return $count > 0;
    }

    public function setDateNumber()
    {
        $seting = Settings::find(1);
        if(!$seting){
            return 3;
        }

        return $seting->value['interval_time'] ?: 3;
    }
}