<?php namespace App\Services\Notify\Content;
class NotifyStaffNewOrder implements NotifyContentContract {


    /**
     * @return string
     */
    public static function getSmsContent()
    {
        return '您好，您有一笔新的订单需要派送！【燕塘优鲜达】';
    }

    /**
     * @return array
     */

    public static function getWeixinTemplateID()
    {
        return 's5bc_2EjFPYNEMdI5yFk3bRtY_edeCjDQ-3cGU1o9xs';
    }

    public static function getWeixinTemplateColor()
    {
        return '#f7f7f7';
    }

    public static function getWeixinTemplateData($preorder = null)
    {
        return [
            "first" => "您好，您有一笔新的订单需要派送",
            "keyword1" => $preorder['name'],
            "keyword2" => $preorder['phone'],
            "keyword3" => $preorder['address'],
            "keyword4" => $preorder['start_time'],
            'remark' => ''
        ];
    }

    public static function getWeixinTemplateUrl($preorder = null)
    {
        //return 'http://yt.l43.cn/yt-staff/deliver/orders';
        return 'http://yt2.l43.cn/yt-staff/?#!/deliver/orders';
    }

    /**
     * @param $entity
     * @return false|string
     */
    public static function getSmsContact($entity)
    {
        return false;
    }
}
