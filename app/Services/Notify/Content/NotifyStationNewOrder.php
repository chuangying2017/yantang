<?php namespace App\Services\Notify\Content;


class NotifyStationNewOrder implements NotifyContentContract {


    /**
     * @return string
     */
    public static function getSmsContent()
    {
        return '您好，您有一笔新的订单，请及时处理！【燕塘优鲜达】';
    }

    /**
     * @return array
     */

    public static function getWeixinTemplateID()
    {
        return '3VxglwTgNjIVpf3EhH_ogchL0L5SODXijkEnSuNqvbs';
    }

    public static function getWeixinTemplateColor()
    {
        return '#f7f7f7';
    }

    public static function getWeixinTemplateData($preorder = null)
    {
        return [
            "first" => "您好，您有一笔新的订单，请及时处理",
            "keyword1" => $preorder['name'],
            "keyword2" => $preorder['phone'],
            "keyword3" => $preorder['address'],
            "keyword4" => $preorder['start_time'],
            'remark' => '请在12小时内处理订单!'
        ];
    }

    public static function getWeixinTemplateUrl($preorder = null)
    {
        //return 'http://station.yt.weazm.com/station/subscribes/' . $preorder['id'] . '/setting';
        return 'http://yt2.l43.cn/station/subscribes/' . $preorder['id'] . '/setting';
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
