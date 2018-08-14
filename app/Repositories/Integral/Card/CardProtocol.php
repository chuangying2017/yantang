<?php
namespace App\Repositories\Integral\Card;

class CardProtocol
{
    const NEW_MEMBER_STATUS = '1';//新用户不可领取
    const OLD_MEMBER_STATUS = '0';//不限制会员领取
    const CARD_PAGINATE_NUM = '20';//分页数量

    const CARD_RACKING_UP = 'show';//上架 展示
    const CARD_RACKING_DOWN = 'hide';//下架 隐藏
    const CARD_TYPE_LIMITS = 'limits';//限制领取数量
    const CARD_TYPE_LOOSE = 'loose'; //宽松 不限制

    const CARD_MODE_DEFAULT = '0';//用户可以领取
    const CARD_MODE_ONE = '1';//新用户不可领取

    public static $card_array_data = [
        'status'        =>  self::CARD_RACKING_UP,
        'mode'          =>  self::CARD_MODE_DEFAULT,
        'type'          =>  self::CARD_TYPE_LOOSE,
    ];

}