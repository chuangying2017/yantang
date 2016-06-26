<?php namespace App\Services\Subscribe;


class PreorderProtocol
{

    const PREORDER_PER_PAGE = 20;

    //status对应的值
    const ORDER_STATUS_OF_PENDING = 'pending';
    const ORDER_STATUS_OF_DONE = 'done';
    const ORDER_STATUS_OF_READY = 'ready';
    const ORDER_STATUS_OF_FEATURE = 'feature';

    //charge_status 对应的值
    const CHARGE_STATUS_OF_NULL = 0;
    const CHARGE_STATUS_OF_OK = 1;
    const CHARGE_STATUS_OF_NOT_ENOUGH = 2;

    //status对应的值
    const ASSIGN_STATUS_OF_UNTREATED = 'untreated';
    const ASSIGN_STATUS_OF_NOT_SET = 'not_set';
    const ASSIGN_STATUS_OF_NO_STAFF = 'no_staff';
    const ASSIGN_STATUS_OF_CONFIRM = 'confirm';
    const ASSIGN_STATUS_OF_REJECT = 'reject';

    const DAYTIME_OF_AM = 0;
    const DAYTIME_OF_PM = 1;


    public static function status($key = null)
    {
        $message = [
            self::STATUS_OF_UNTREATED => '未处理',
            self::STATUS_OF_NOT_SET => '未设置',
            self::STATUS_OF_NO_STAFF => '无配送员',
            self::STATUS_OF_NORMAL => '正常状态',
            self::STATUS_OF_PAUSE => '暂停',
            self::STATUS_OF_REJECT => '已拒绝',
        ];

        return is_null($key) ? $message : $message[$key];
    }

    public static function preorderStatusName($status = null, $charge_status = null)
    {
        if ($status == self::STATUS_OF_NORMAL && $charge_status == self::STATUS_OF_NOT_ENOUGH) {
            return self::status($charge_status);
        }

        return is_null($status) ? "数据异常" : self::status($status);
    }


    public static function stationPreorderMenus($key = null)
    {
        $message = [
            self::STATUS_OF_UNTREATED => '未处理',
            self::STATUS_OF_NORMAL => '正常状态',
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

    public static function weekPauseName($week_key = null)
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
        if (is_null($week_key)) {
            return $array;
        }
        $array = array_where($array, function ($key, $value) use ($week_key) {
            if ($week_key > 0 && $key == 0) {
                return $value;
            }
            if ($key > $week_key) {
                return $value;
            }
        });

        return $array;
    }

}
