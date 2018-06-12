<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/12/012
 * Time: 15:19
 */

namespace App\Services\Notify\Content;


class NotifyStaffCommentAlert implements NotifyContentContract
{

    /**
     * @return string
     */
    public static function getSmsContent()
    {
        // TODO: Implement getSmsContent() method.
    }

    /**
     * @param $entity
     * @return false|string
     */
    public static function getSmsContact($entity)
    {
        // TODO: Implement getSmsContact() method.
    }

    /**
     * @return array
     */
    public static function getWeixinTemplateID()
    {
        // TODO: Implement getWeixinTemplateID() method.
        return 'TjSF4V6iBdPCDV-x9itBvqlGU6KFHufAzBsY3dH8ldA';
    }

    public static function getWeixinTemplateUrl($entity = null)
    {
        // TODO: Implement getWeixinTemplateUrl() method.
        return null;
        //return 'http://yt2.l43.cn/yt-client/?#!/subscribe/orders/'. $entity['id'];
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
            'first' => '您好 有新的评论哦 可以在您的登录站点查看',
            'keyword1'=>'匿名评论',
            'keyword2'=>isset($entity->comments[0]->updated_at)?$entity->comments[0]->updated_at:date('Y-m-d H:i:s',time()),
            'remark'=>'燕塘优先达达',
        ];
    }
}