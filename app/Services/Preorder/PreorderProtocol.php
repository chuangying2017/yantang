<?php namespace App\Services\Preorder;


class PreorderProtocol
{

    const PREORDER_PER_PAGE = 20;

    //status对应的值
    const ORDER_STATUS_OF_ASSIGNING = 'assigning';
    const ORDER_STATUS_OF_SHIPPING = 'shipping';
    const ORDER_STATUS_OF_DONE = 'done';

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


    public static function status($key = null)
    {
        $message = [

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
