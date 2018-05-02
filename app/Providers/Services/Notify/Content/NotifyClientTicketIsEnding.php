<?php namespace App\Services\Notify\Content;
class NotifyClientTicketIsEnding implements NotifyContentContract {

    /**
     * @return string
     */
    public static function getSmsContent()
    {
        return '亲爱的客户，您的优惠券即将过期，有需要请及时使用。【燕塘优鲜达】';
    }

    /**
     * @return array
     */
    public static function getWeixinTemplateID()
    {
        return 'vzAEgEOhPWTIlXoAf4erN1ZMdcKfcnijhkxFz5suJZs';
    }

    public static function getWeixinTemplateUrl($ticket = null)
    {
        return 'http://yt.l43.cn/yt-client/?#!/coupons/list/usable';
    }

    public static function getWeixinTemplateColor()
    {
        return '#f7f7f7';
    }

    public static function getWeixinTemplateData($ticket = null)
    {
        return [
            'first' => '亲爱的客户，您的优惠券即将过期，有需要请及时使用。',
            'keyword1' => $ticket['end_time'],
            'keyword2' => '即将到期',
            'remark' => '点击查看详情'
        ];
    }

    /**
     * @param $entity
     * @return false|string
     */
    public static function getSmsContact($ticket)
    {
        return null;
    }

}
