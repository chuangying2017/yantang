<?php namespace App\Repositories;
class NoGenerator {

    const ORDER_PREFIX = '10';
    const ORDER_BILLING_PREFIX = '11';

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


}
