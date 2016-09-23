<?php namespace App\Services\Notify\Config;

interface NotifyConfigContract {
    

    /**
     * @return string
     */
    public static function sms();

    /**
     * @return array
     */
    public static function weixin($user_id, $entity = null);


}
