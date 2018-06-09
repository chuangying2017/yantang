<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/9/009
 * Time: 18:06
 */

namespace App\Services\Notify\Content;


class NotifyClientOrderCommentAlert implements NotifyContentContract
{

    /**
     * @return string
     */
    public static function getSmsContent()
    {
        // TODO: Implement getSmsContent() method.
        return '亲爱的客户，您的订单已被接受，您可在“个人中心-我的奶卡”对订单进行评价送有积分，谢谢！【燕塘优鲜达】';
    }

    /**
     * @param $entity
     * @return false|string
     */
    public static function getSmsContact($preorder)
    {
        // TODO: Implement getSmsContact() method.
        return $preorder['phone'];
    }

    /**
     * @return array
     */
    public static function getWeixinTemplateID()
    {
        // TODO: Implement getWeixinTemplateID() method.
        return 'TjSF4V6iBdPCDV-x9itBvqlGU6KFHufAzBsY3dH8ldA';
    }

    public static function getWeixinTemplateUrl($preorder = null)
    {
        // TODO: Implement getWeixinTemplateUrl() method.
        return 'http://yt2.l43.cn/yt-client/?#!/subscribe/orders/'. $preorder['id'];
    }

    public static function getWeixinTemplateColor()
    {
        // TODO: Implement getWeixinTemplateColor() method.
        return '#f7f7f7';
    }

    public static function getWeixinTemplateData($entity = null)
    {
        // TODO: Implement getWeixinTemplateData() method.
        return [
            'first' => '您好 下单成功欢迎评论',
            'keyword1'=>$entity['order_no'],
            'keyword2'=>$entity['start_time'],
            'remark'=>'您可在奶卡那边对订单进行评价',
        ];
    }
}