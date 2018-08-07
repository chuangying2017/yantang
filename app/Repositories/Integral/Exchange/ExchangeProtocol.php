<?php
namespace App\Repositories\Integral\Exchange;

class ExchangeProtocol
{
    const CONVERT_TYPE_INTEGRAL = 'integral';//兑换类型默认积分
    const CONVERT_STATUS_UP = '1';//默认上架
    const CONVERT_STATUS_DOWN = '0';//下架
    const CONVERT_NEW_MEMBER_DRAW = '0';//全部会员可领取
    const CONVERT_OLD_MEMBER_DRAW = '1';//新用户不可领取

    public static $status = ['status'=>['0'=>'下架','1'=>'上架']];

    public static $exchangeArray = [
        'cost_integral',
        'promotions_id',
        'valid_time',
        'deadline_time',
        'status',
        'type',
        'issue_num',
        'draw_num',
        'remain_num',
        'delayed',
        'cover_image',
        'member_type',
        'limit_num',
        'name',
        'description'
    ];
}