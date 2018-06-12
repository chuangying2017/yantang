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
    }

    public static function getWeixinTemplateUrl($entity = null)
    {
        // TODO: Implement getWeixinTemplateUrl() method.
    }

    public static function getWeixinTemplateColor()
    {
        // TODO: Implement getWeixinTemplateColor() method.
    }

    public static function getWeixinTemplateData($entity = null)
    {
        // TODO: Implement getWeixinTemplateData() method.
    }
}