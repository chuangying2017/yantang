<?php
namespace App\Repositories\Integral\Exchange;

class ExchangeProtocol
{
    const CONVERT_TYPE_INTEGRAL = 'integral';//兑换类型默认积分
    const CONVERT_STATUS_UP = '1';//默认上架
    const CONVERT_STATUS_DOWN = '0';//下架
    const CONVERT_NEW_MEMBER_DRAW = '0';//全部会员可领取
    const CONVERT_OLD_MEMBER_DRAW = '1';//新用户不可领取
}