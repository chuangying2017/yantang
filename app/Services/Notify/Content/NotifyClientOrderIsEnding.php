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
        return 'QtdTuehBTXjy86Qf6ULjVpNKBpLxff-rxUbsrQ5JkzI';
    }

    public static function getWeixinTemplateUrl($preorder = null)
    {
        return 'http://client.yt.weazm.com/?#!/subscribe/orders/' . $preorder['id'];
    }

    public static function getWeixinTemplateColor()
    {
        return '#f7f7f7';
    }

    public static function getWeixinTemplateData($preorder = null)
    {
        return [
            'first' => '亲爱的客户，您的订单即将结束',
            'keyword1' => $preorder['order_no'],
            'keyword2' => $preorder['start_time'],
            'keyword3' => $preorder['end_time'],
            'remark' => '数据仅供参考,有异议请联系客服或配送服务部,您可在“个人中心-我的奶卡”查看具体信息'
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
