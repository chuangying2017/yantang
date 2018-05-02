<?php namespace App\Services\Notify\Content;
class NotifyClientOrderIsEnding implements NotifyContentContract {

    /**
     * @return string
     */
    public static function getSmsContent()
    {
        return '亲爱的客户，您的订单即将结束配送，有需要请及时下新订单。【燕塘优鲜达】';
    }

    /**
     * @return array
     */
    public static function getWeixinTemplateID()
    {
        return 'xiaQKzsvLJILHVRY8eGbLwtRK4R0_31tv6isuM-s7Bc';
    }

    public static function getWeixinTemplateUrl($preorder = null)
    {
        return 'http://yt.l43.cn/yt-client/?#!/subscribe/orders/' . $preorder['id'];
    }

    public static function getWeixinTemplateColor()
    {
        return '#f7f7f7';
    }

    public static function getWeixinTemplateData($preorder = null)
    {
        return [
            'first' => '订单到期提醒',
            'keyword1' => $preorder['order_no'],
            'keyword2' => $preorder['start_time'],
            'keyword3' => $preorder['end_time'],
            'remark' => '尊敬的用户，您的送奶到家服务要结束了。别忘了续订哦~鲜奶到家，就选燕塘优鲜达，专人配送，全程冷链，优鲜为你。'
        ];
    }

    /**
     * @param $entity
     * @return false|string
     */
    public static function getSmsContact($preorder)
    {
        return $preorder['phone'];
    }

}
