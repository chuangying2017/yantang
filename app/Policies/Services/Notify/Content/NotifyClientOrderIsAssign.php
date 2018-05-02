<?php namespace App\Services\Notify\Content;
class NotifyClientOrderIsAssign implements NotifyContentContract {

    /**
     * @return string
     */
    public static function getSmsContent()
    {
        return '亲爱的客户，您的订单已被接受，您可在“个人中心-我的奶卡”查看具体信息；稍后我们会有专人与您联系，谢谢！【燕塘优鲜达】';
    }

    /**
     * @return array
     */
    public static function getWeixinTemplateID()
    {
        return 'GOygLfTLTGen2Z1o_OK-MDzFDNkr7F4O6ssAVCKXq2I';
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
            'first' => '亲爱的客户，您的订单已被接受',
            'keyword1' => '订单号: ' . $preorder['order_no'],
            'keyword2' => '已确认',
            'keyword3' => $preorder['confirm_at'],
            'remark' => '您可在“个人中心-我的奶卡”查看具体信息,稍后我们会有专人与您联系'
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
