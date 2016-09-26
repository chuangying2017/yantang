<?php namespace App\Services\Notify\Content;

use Carbon\Carbon;

class NotifyStationAdminOrderReject implements NotifyContentContract {

    /**
     * @return string
     */
    public static function getSmsContent()
    {
        return '您有一个来自服务部的被拒绝订单，请及时处理！【燕塘优鲜达】';
    }

    /**
     * @return array
     */

    public static function getWeixinTemplateID()
    {
        return 'zVDXnM13jT-z7k2jUJ-mzHfDlhpuL3r2ZW1bNsYAW98';
    }

    public static function getWeixinTemplateColor()
    {
        return '#f7f7f7';
    }

    public static function getWeixinTemplateData($preorder = null)
    {
        return [
            "first" => "服务部拒绝订单，请及时处理",
            "keyword1" => $preorder['order_no'],
            "keyword2" => $preorder['create_at'],
            "keyword3" => Carbon::now(),
            "keyword4" => isset($preorder['assign']['memo']) ? $preorder['assign']['memo'] : '无',
            'remark' => '请登录后台处理订单'
        ];
    }

    public static function getWeixinTemplateUrl($preorder = null)
    {
        return '#';
    }

    /**
     * @param $entity
     * @return false|string
     */
    public static function getSmsContact($entity)
    {
        return env('STATION_ADMIN_PHONE', '15918821560');
    }
}
