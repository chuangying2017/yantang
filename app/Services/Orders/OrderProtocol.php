<?php namespace App\Services\Orders;

use App\Services\Orders\Exceptions\WrongStatus;

class OrderProtocol {

    const CHILD_ORDER_LENGTH = 19;
    const MAIN_ORDER_LENGTH = 18;

    const STATUS_OF_UNPAID = 'unpaid';
    const STATUS_OF_PAID = 'paid';
    const STATUS_OF_DELIVER = 'deliver';
    const STATUS_OF_DONE = 'done';
    const STATUS_OF_REVIEW = 'review';
    const STATUS_OF_CANCEL = 'cancel';

    const STATUS_OF_RETURN_APPLY = 'apply';
    const STATUS_OF_RETURN_APPROVE = 'approve';
    const STATUS_OF_RETURN_REJECT = 'reject';
    const STATUS_OF_RETURN_DELIVER = 'redeliver';
    const STATUS_OF_REFUNDING = 'refunding';
    const STATUS_OF_REFUNDED = 'refunded';

    const PAY_ONLINE = 'online';
    const PAY_CASH = 'cash';

    const TYPE_OF_DISCOUNT = 'discount';
    const TYPE_OF_MAIN = 'main';

    const RESOURCE_OF_TICKET = 'App\Models\Ticket';
    const RESOURCE_OF_PINGXX = 'App\Models\PingxxPayment';


    const ACTION_OF_RETURN_REJECT = 'reject';
    const ACTION_OF_RETURN_APPROVE = 'approve';

    public static function statusAfterPaid()
    {
        return [
            self::STATUS_OF_PAID,
            self::STATUS_OF_DONE,
            self::STATUS_OF_DELIVER,
            self::STATUS_OF_REFUNDING,
            self::STATUS_OF_RETURN_DELIVER,
            self::STATUS_OF_REFUNDED,
        ];
    }


    public static function status($key = null)
    {
        $message = [
            self::STATUS_OF_UNPAID         => '未支付',
            self::STATUS_OF_PAID           => '已支付',
            self::STATUS_OF_DELIVER        => '已发货',
            self::STATUS_OF_RETURN_DELIVER => '已退货',
            self::STATUS_OF_DONE           => '已完成',
            self::STATUS_OF_CANCEL         => '已取消',
            self::STATUS_OF_REVIEW         => '已评价',
            self::STATUS_OF_REFUNDING      => '退款中',
            self::STATUS_OF_RETURN_APPLY   => '退货,待审核',
            self::STATUS_OF_RETURN_APPROVE => '退货,同意',
            self::STATUS_OF_RETURN_REJECT  => '退货,拒绝',
            self::STATUS_OF_REFUNDED       => '已退款',
        ];

        return is_null($key) ? $message : $message[ $key ];
    }

    public static function statusIs($need_status, $current_status)
    {
        $valid_status = [$need_status];
        switch ($current_status) {
            case self::STATUS_OF_PAID:
                $valid_status = [self::STATUS_OF_PAID, self::STATUS_OF_DELIVER, self::STATUS_OF_DONE];
                break;
            case self::STATUS_OF_DELIVER:
                $valid_status = [self::STATUS_OF_DELIVER, self::STATUS_OF_DONE];
                break;
            case self::STATUS_OF_UNPAID:
                $valid_status = [self::STATUS_OF_UNPAID];
                break;
            default:
                break;
        }


        if ( ! in_array($current_status, $valid_status)) {
            return false;
        }

        return true;
    }

    public static function validStatus($from_status, $to_status, $exception = true)
    {
        $valid_status = [];
        switch ($to_status) {
            case self::STATUS_OF_PAID:
                $valid_status = [self::STATUS_OF_UNPAID, self::STATUS_OF_PAID, self::STATUS_OF_DELIVER];
                break;
            case self::STATUS_OF_DELIVER:
                $valid_status = [self::STATUS_OF_PAID];
                break;
            case self::STATUS_OF_DONE:
                $valid_status = [self::STATUS_OF_DELIVER];
                break;
            case self::STATUS_OF_CANCEL:
                $valid_status = [self::STATUS_OF_UNPAID, self::STATUS_OF_PAID];
                break;
            case self::STATUS_OF_REVIEW:
                $valid_status = [self::STATUS_OF_DONE];
                break;
            case self::STATUS_OF_REFUNDING:
                $valid_status = [self::STATUS_OF_PAID, self::STATUS_OF_DELIVER, self::STATUS_OF_RETURN_DELIVER, self::STATUS_OF_DONE, self::STATUS_OF_REVIEW];
                break;
            case self::STATUS_OF_REFUNDED:
                $valid_status = [self::STATUS_OF_REFUNDING];
                break;
            case self::STATUS_OF_UNPAID:
                $valid_status = [self::STATUS_OF_UNPAID];
                break;
            case self::STATUS_OF_RETURN_APPLY:
                $valid_status = [self::STATUS_OF_PAID, self::STATUS_OF_DELIVER, self::STATUS_OF_REVIEW, self::STATUS_OF_DONE];
                break;
            default:
                break;
        }


        if ( ! in_array($from_status, $valid_status)) {
            if ($exception) {
                throw new WrongStatus($from_status, $to_status);
            } else {
                return false;
            }
        }

        return true;
    }


}
