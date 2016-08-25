<?php namespace App\Services\Order;
class OrderProtocol {

    const ORDER_PER_PAGE = 10;

    const STATUS_OF_UNPAID = 'unpaid';
    const STATUS_OF_PAYMENT_VERIFY = 'pay_verify';
    const STATUS_OF_PAID = 'paid';
    const STATUS_OF_INFO_VERIFY = 'info_verify';
    const STATUS_OF_CANCEL = 'cancelled';
    const STATUS_OF_BACKLOG = 'backlog';
    const STATUS_OF_SHIPPING = 'shipping';
    const STATUS_OF_SHIPPED = 'shipped';
    const STATUS_OF_DONE = 'done';

    const PAID_STATUS_OF_UNPAID = 'unpaid';
    const PAID_STATUS_OF_PARTIAL = 'partial';
    const PAID_STATUS_OF_PENDING = 'pending';
    const PAID_STATUS_OF_PAID = 'paid';

    const REFUND_STATUS_OF_DEFAULT = 'none';
    const REFUND_STATUS_OF_APPLY = 'apply';
    const REFUND_STATUS_OF_REJECT = 'reject';
    const REFUND_STATUS_OF_APPROVE = 'approve';
    const REFUND_STATUS_OF_SHIPPING = 'shipping';
    const REFUND_STATUS_OF_SHIPPED = 'shipped';
    const REFUND_STATUS_OF_REFUNDING = 'refunding';
    const REFUND_STATUS_OF_DONE = 'done';
    const REFUND_STATUS_OF_FAIL = 'fail';

    //Billing
    const BILLING_TYPE_OF_MONEY = 'money';
    const BILLING_TYPE_OF_CREDITS = 'credits';

    //Order Type
    const ORDER_TYPE_OF_MALL_MAIN = 0;
    const ORDER_TYPE_OF_MALL_CHILD = 1;
    const ORDER_TYPE_OF_REFUND = 2;
    const ORDER_TYPE_OF_CAMPAIGN = 3;
    const ORDER_TYPE_OF_SUBSCRIBE = 4;

    //Deliver type
    const DELIVER_TYPE_OF_EXPRESS = 'express';
    const DELIVER_TYPE_OF_STORE = 'store';

    //Pay type
    const PAY_TYPE_OF_ONLINE = 'online';

    //ticket status
    const ORDER_TICKET_STATUS_OF_OK = 'ok';
    const ORDER_TICKET_STATUS_OF_OVERTIME = 'overtime';
    const ORDER_TICKET_STATUS_OF_USED = 'used';

    //order promotion
    const ORDER_PROMOTION_STATUS_OF_DONE = 'done';
    const ORDER_PROMOTION_STATUS_OF_PENDING = 'pending';
    const ORDER_PROMOTION_STATUS_OF_CANCEL = 'cancel';

    public static function getDeliverType($order_type)
    {
        if ($order_type == self::ORDER_TYPE_OF_CAMPAIGN) {
            return self::DELIVER_TYPE_OF_STORE;
        }

        return self::DELIVER_TYPE_OF_EXPRESS;
    }

    public static function validOrderTicketStatus($status)
    {
        return in_array($status, [
            self::ORDER_TICKET_STATUS_OF_OK,
            self::ORDER_TICKET_STATUS_OF_OVERTIME,
            self::ORDER_TICKET_STATUS_OF_USED
        ]);
    }

    public static function validOrderStatus($status)
    {
        return in_array($status, [
            self::STATUS_OF_UNPAID,
            self::STATUS_OF_PAYMENT_VERIFY,
            self::STATUS_OF_PAID,
            self::STATUS_OF_INFO_VERIFY,
            self::STATUS_OF_CANCEL,
            self::STATUS_OF_BACKLOG,
            self::STATUS_OF_SHIPPING,
            self::STATUS_OF_SHIPPED,
            self::STATUS_OF_DONE,
        ]);
    }

    public static function validOrderPaidStatus($status)
    {
        return in_array($status, [
            self::PAID_STATUS_OF_PAID,
            self::PAID_STATUS_OF_UNPAID,
            self::PAID_STATUS_OF_PARTIAL
        ]);
    }

    public static function validStatusCanRefund($status, $refund_status)
    {
        return in_array($status, [
            self::STATUS_OF_PAID,
            self::STATUS_OF_SHIPPING,
            self::STATUS_OF_SHIPPED,
            self::STATUS_OF_DONE
        ]) && in_array($refund_status, [
            self::REFUND_STATUS_OF_DEFAULT,
            self::REFUND_STATUS_OF_REJECT
        ]);
    }

    public static function statusIs($need_status, $current_status)
    {
        $valid_status = [$need_status];
        switch ($current_status) {
            case self::STATUS_OF_PAID:
                $valid_status = [self::STATUS_OF_PAID, self::STATUS_OF_SHIPPING, self::STATUS_OF_SHIPPED, self::STATUS_OF_DONE];
                break;
            case self::STATUS_OF_SHIPPING:
                $valid_status = [self::STATUS_OF_SHIPPING, self::STATUS_OF_SHIPPED, self::STATUS_OF_DONE];
                break;
            case self::STATUS_OF_UNPAID:
                $valid_status = [self::STATUS_OF_UNPAID, self::STATUS_OF_CANCEL];
                break;
            case self::STATUS_OF_CANCEL:
                $valid_status = [self::STATUS_OF_UNPAID, self::STATUS_OF_PAID, self::STATUS_OF_CANCEL];
                break;
            default:
                break;
        }


        if (!in_array($current_status, $valid_status)) {
            return false;
        }

        return true;
    }


}
