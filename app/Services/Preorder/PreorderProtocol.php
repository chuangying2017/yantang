<?php namespace App\Services\Preorder;


class PreorderProtocol {

    const PREORDER_PER_PAGE = 20;

    //status对应的值
    const ORDER_STATUS_OF_ASSIGNING = 'assigning';
    const ORDER_STATUS_OF_SHIPPING = 'shipping';
    const ORDER_STATUS_OF_DONE = 'done';
    const ORDER_STATUS_OF_CANCEL = 'cancel';

    //charge_status 对应的值
    const CHARGE_STATUS_OF_NULL = 0;
    const CHARGE_STATUS_OF_OK = 1;
    const CHARGE_STATUS_OF_NOT_ENOUGH = 2;

    //status对应的值
    const ASSIGN_STATUS_OF_UNTREATED = 'untreated';
    const ASSIGN_STATUS_OF_CONFIRM = 'confirm';
    const ASSIGN_STATUS_OF_REJECT = 'reject';
    const DAYS_OF_ASSIGN_DISPOSE = 2;

    const DAYTIME_OF_AM = 0;
    const DAYTIME_OF_PM = 1;


    public static function validOrderStatus($status)
    {
        if(is_null($status)) {
            return false;
        }
        return in_array($status, [
            self::ORDER_STATUS_OF_SHIPPING,
            self::ORDER_STATUS_OF_ASSIGNING,
            self::ORDER_STATUS_OF_DONE,
            self::ORDER_STATUS_OF_CANCEL,
        ]);
    }

    public static function validChargeStatus($status)
    {
        if(is_null($status)) {
            return false;
        }

        return in_array($status, [
            self::CHARGE_STATUS_OF_NULL,
            self::CHARGE_STATUS_OF_NOT_ENOUGH,
            self::CHARGE_STATUS_OF_OK
        ]);
    }

    public static function status($key = null)
    {
        $message = [
            self::ORDER_STATUS_OF_SHIPPING => '配送中',
            self::ORDER_STATUS_OF_ASSIGNING => '分配服务部中',
            self::ORDER_STATUS_OF_DONE => '已完成',
            self::ORDER_STATUS_OF_CANCEL => '已结束',
        ];

        return is_null($key) ? $message : $message[$key];
    }


    public static function weekName($key = null)
    {
        $array = [
            0 => 'sun',
            1 => 'mon',
            2 => 'tue',
            3 => 'wed',
            4 => 'thu',
            5 => 'fri',
            6 => 'sat',
        ];

        return is_null($key) ? $array : $array[$key];
    }

}
