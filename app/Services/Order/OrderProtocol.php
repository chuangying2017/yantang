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
    const PAID_STATUS_OF_PAID = 'paid';

    const REFUND_STATUS_OF_APPLY = 'apply';
    const REFUND_STATUS_OF_REJECT = 'reject';
    const REFUND_STATUS_OF_APPROVE = 'approve';
    const REFUND_STATUS_OF_SHIPPING = 'shipping';
    const REFUND_STATUS_OF_SHIPPED = 'shipped';
    const REFUND_STATUS_OF_DONE = 'done';


    //Billing
    const BILLING_TYPE_OF_MONEY = 'money';
    const BILLING_TYPE_OF_CREDITS = 'credits';

    //Order Type
    const ORDER_TYPE_OF_MALL_MAIN = 0;
    const ORDER_TYPE_OF_MALL_CHILD = 1;
    const ORDER_TYPE_OF_MALL_REFUND = 2;
    const ORDER_TYPE_OF_CAMPAIGN= 3;
}
