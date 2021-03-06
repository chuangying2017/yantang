<?php namespace App\Services\Preorder;


class PreorderProtocol {

    const PREORDER_PER_PAGE = 20;

    //status对应的值
    const ORDER_STATUS_OF_UNPAID = 'unpaid';
    const ORDER_STATUS_OF_ASSIGNING = 'assigning';
    const ORDER_STATUS_OF_SHIPPING = 'shipping';
    const ORDER_STATUS_OF_DONE = 'done';
    const ORDER_STATUS_OF_CANCEL = 'cancel';

    //charge_status 对应的值
    const CHARGE_STATUS_OF_NULL = 0;
    const CHARGE_STATUS_OF_OK = 1;
    const CHARGE_STATUS_OF_NOT_ENOUGH = 2;

    //配送周期类型
    const WEEKDAY_TYPE_OF_ALL = 'all';
    const WEEKDAY_TYPE_OF_WORKDAY = 'workday';
    const WEEKDAY_TYPE_OF_WEEKEND = 'weekend';


    //status对应的值
    const ASSIGN_STATUS_OF_UNTREATED = 'untreated';
    const ASSIGN_STATUS_OF_OVERTIME = 'overtime';
    const ASSIGN_STATUS_OF_CONFIRM = 'confirm';
    const ASSIGN_STATUS_OF_ASSIGNED = 'assign';
    const ASSIGN_STATUS_OF_REJECT = 'reject';
    const HOURS_OF_ASSIGN_DISPOSE_HOURS = 12;

    const DAYTIME_OF_AM = 1;
    const DAYTIME_OF_PM = 0;

    //Deliver
    const PREORDER_DELIVER_STATUS_OF_NEED_CONFIRM = 0;
    const PREORDER_DELIVER_STATUS_OF_OK = 1;
    const PREORDER_DELIVER_STATUS_OF_ERROR = 2;

    //Time name
    const TIME_NAME_OF_CONFIRM = 'confirm_at';
    const TIME_NAME_OF_CREATE = 'created_at';
    const TIME_NAME_OF_PAY = 'pay_at';
    const TIME_NAME_OF_DONE = 'done_at';

    //new addition comment identify
    const COMMENT_IDENTIFY = 1;
    const COMMENT_IDENTIFY_TWO = 2;
    const COMMENT_IDENTIFY_THREE = 3; //alert lost send template 最后一次模板消息发送 表示没有评论 订单已经done 进行最后一次模板信息提醒

    public static function validOrderStatus($status)
    {
        if (is_null($status)) {
            return false;
        }
        return in_array($status, [
            self::ORDER_STATUS_OF_UNPAID,
            self::ORDER_STATUS_OF_SHIPPING,
            self::ORDER_STATUS_OF_ASSIGNING,
            self::ORDER_STATUS_OF_DONE,
            self::ORDER_STATUS_OF_CANCEL,
        ]);
    }

    public static function validOrderAssignStatus($status, $assigning = false)
    {
        if (is_null($status)) {
            return false;
        }

        return $assigning ?
            in_array($status, [
                self::ASSIGN_STATUS_OF_UNTREATED,
                self::ASSIGN_STATUS_OF_REJECT,
                self::ASSIGN_STATUS_OF_OVERTIME,
            ]) :
            in_array($status, [
                self::ASSIGN_STATUS_OF_OVERTIME,
                self::ASSIGN_STATUS_OF_UNTREATED,
                self::ASSIGN_STATUS_OF_CONFIRM,
                self::ASSIGN_STATUS_OF_ASSIGNED,
                self::ASSIGN_STATUS_OF_REJECT,
            ]);
    }

    public static function validChargeStatus($status)
    {
        if (is_null($status)) {
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
        $data = [
            self::ORDER_STATUS_OF_UNPAID => '未支付',
            self::ORDER_STATUS_OF_SHIPPING => '配送中',
            self::ORDER_STATUS_OF_ASSIGNING => '分配服务部中',
            self::ORDER_STATUS_OF_DONE => '已完成',
            self::ORDER_STATUS_OF_CANCEL => '已结束',
        ];

        return is_null($key) ? $data : array_get($data, $key, '其他');
    }


    public static function weekdayType($key = null)
    {
        $data = [
            self::WEEKDAY_TYPE_OF_ALL => '每天',
            self::WEEKDAY_TYPE_OF_WORKDAY => '工作日',
            self::WEEKDAY_TYPE_OF_WEEKEND => '周末',
        ];

        return is_null($key) ? $data : $data[$key];
    }

    public static function statusTimeTag($status)
    {
        $data = [
            self::ORDER_STATUS_OF_ASSIGNING => 'pay_at',
            self::ORDER_STATUS_OF_DONE => 'done_at',
            self::ORDER_STATUS_OF_SHIPPING => 'confirm_at',
            self::ORDER_STATUS_OF_CANCEL => 'cancel_at'
        ];

        return array_get($data, $status, null);
    }


    public static function preorderIsPaid($status)
    {
        return !in_array($status, [self::ORDER_STATUS_OF_UNPAID]);
    }
}
