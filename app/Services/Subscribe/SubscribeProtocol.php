<?php namespace App\Services\Subscribe;


class SubscribeProtocol
{
    const STATEMENTS_STATUS_OF_OK = 'ok';
    const STATEMENTS_STATUS_OF_PENDING = 'pending';
    const STATEMENTS_STATUS_OF_ERROR = 'error';


    public static function statements_status($key = null)
    {
        $message = [
            self::STATEMENTS_STATUS_OF_OK => '已对账',
            self::STATEMENTS_STATUS_OF_PENDING => '未对账',
            self::STATEMENTS_STATUS_OF_ERROR => '有异议',
        ];

        return is_null($key) ? $message : $message[$key];
    }

}
