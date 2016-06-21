<?php namespace App\Repositories;
class NoGenerator {

    const ORDER_PREFIX = '10';
    const ORDER_BILLING_PREFIX = '11';
    const ORDER_TICKET_PREFIX = '12';

    const LENGTH_OF_ORDER_BILLING_NO = 21;
    const LENGTH_OF_ORDER_NO = 21;

    private static function generate_no()
    {
        return mt_rand(1, 9) . substr(date('Y'), -2) . date('md') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', mt_rand(0, 99));
    }

    public static function generateOrderNo()
    {
        return self::ORDER_PREFIX . self::generate_no();
    }

    public static function generateOrderBillingNo()
    {
        return self::ORDER_BILLING_PREFIX . self::generate_no();
    }

    public static function generateOrderTicketNo($order_no = null)
    {
        return is_null($order_no) ? self::generateOrderNo() . self::ORDER_TICKET_PREFIX : $order_no . self::ORDER_TICKET_PREFIX;
    }

    public static function isOrderNo($order_no)
    {
        return strlen($order_no) == 21;
    }

    public static function isOrderBillingNo($billing_no)
    {
        return strlen($billing_no) == 21;
    }

    public static function isOrderTicketNo($no)
    {
        return strlen($no) == 23;
    }

}
