<?php namespace App\Services\Notify\Content;
class NotifyStationAdminAssignOvertime implements NotifyContentContract {

    /**
     * @return string
     */
    public static function getSmsContent()
    {
        return '服务部超时未接单，请及时处理！【燕塘优鲜达】';
    }

    /**
     * @param $entity
     * @return false|string
     */
    public static function getSmsContact($entity)
    {
        return env('STATION_ADMIN_PHONE', '15918821560');
    }

    /**
     * @return array
     */
    public static function getWeixinTemplateID()
    {
        return 'V4ULvhLspujIVP9g1vKCDqwbI9ZrEc8n8hPOHEvIdAU';
    }

    public static function getWeixinTemplateUrl($entity = null)
    {
        return '#';
    }

    public static function getWeixinTemplateColor()
    {
        return '#f7f7f7';
    }

    public static function getWeixinTemplateData($entity = null)
    {
        return [
            "first" => "服务部超时未接单，请及时处理",
            "keyword1" => '后台查看',
            "keyword2" => '待处理',
            'remark' => '请登录后台处理订单'
        ];
    }
}
