<?php namespace App\Services\Statement;
class StatementProtocol {

    const TYPE_OF_STORE = 1;
    const TYPE_OF_STATION = 2;


    const STATEMENT_STATUS_OF_OK = 1;
    const STATEMENT_STATUS_OF_ERROR = 2;
    const STATEMENT_STATUS_OF_PENDING = 0;

    const CHECK_STATUS_OF_HANDLED = 1;
    const CHECK_STATUS_OF_PENDING = 0;


    const DATE_OF_STORE_CHECK_DAY = 30;
    const DATE_OF_STATION_CHECK_DAY = 30;

    public static function getStoreCheckDay()
    {
        return self::DATE_OF_STORE_CHECK_DAY;
    }

    public static function getStationCheckDay()
    {
        return self::DATE_OF_STATION_CHECK_DAY;
    }

}
