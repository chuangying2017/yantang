<?php namespace App\Services\Notify\Content;

interface NotifyContentContract {


    /**
     * @return string
     */
    public static function getSmsContent();

    /**
     * @return array
     */

    public static function getWeixinTemplateID();
    
    public static function getWeixinTemplateUrl($entity = null);

    public static function getWeixinTemplateColor();
    
    public static function getWeixinTemplateData($entity = null);
    
    


}
