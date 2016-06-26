<?php namespace App\Repositories;

use Carbon\Carbon;

class NoGenerator {

    const ORDER_PREFIX = '10';
    const ORDER_BILLING_PREFIX = '11';
    const ORDER_TICKET_PREFIX = '12';

    const CHARGE_BILLING_PREFIX = '21';
    const PREORDER_BILLING_PREFIX = '22';

    const ORDER_TICKET_STATEMENT_PREFIX = '31';
    const STATION_STATEMENT_PREFIX = '32';

    const LENGTH_OF_ORDER_BILLING_NO = 21;
    const LENGTH_OF_ORDER_NO = 21;
    const LENGTH_OF_CHARGE_BILLING_NO = 21;
    const LENGTH_OF_PREORDER_BILLING_NO = 21;

    private static function generate_no()
    {
        return mt_rand(1, 9) . substr(date('Y'), -2) . date('md') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', mt_rand(0, 99));
    }

    public static function generateOrderNo()
    {
        return self::ORDER_PREFIX . self::generate_no();
    }

    public static function generateStoreStatementNo($store_id)
    {
        return date('Ymd') . $store_id;
    }

    public static function generateOrderBillingNo()
    {
        return self::ORDER_BILLING_PREFIX . self::generate_no();
    }

    public static function generateChargeBillingNo()
    {
        return self::CHARGE_BILLING_PREFIX . self::generate_no();
    }

    public static function generatePreorderBillingNo()
    {
        return self::PREORDER_BILLING_PREFIX . self::generate_no();
    }

    public static function generateOrderTicketNo($order_no = null)
    {
        return mt_rand(1, 9) . substr(date('Y'), -2) . date('md') . substr(time(), -2) . substr(microtime(), 2, 5) . mt_rand(1, 9);
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
