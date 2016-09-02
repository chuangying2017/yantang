<?php namespace App\Services\Notify;

use Toplan\PhpSms\Sms;

class NotifyProtocol {

    const SMS_TO_STATION_NEW_ORDER = '您好，您有一笔新的订单，请及时处理！【燕塘优鲜达】';

    const SMS_TO_CLIENT_PREORDER_IS_ASSIGNED = '亲爱的客户，您在燕塘优鲜达商城的订单已被接受，您可在“个人中心-我的奶卡”查看为您服务的服务部的具体信息；稍后我们会有专人与您联系，谢谢！【燕塘优鲜达】';

    const SMS_TO_STAFF_PREORDER_IS_ASSIGNED = '收到一个新的订单，请及时处理！【燕塘优鲜达】';

    const SMS_TO_ADMIN_PREORDER_IS_ONT_HANDLE_ON_TIME = '您有一个来自服务部的订单待处理，请及时处理！【燕塘优鲜达】';

    const SMS_TO_ADMIN_PREORDER_PREORDER_IS_REJECT = '您有一个来自服务部的订单待处理，请及时处理！【燕塘优鲜达】';


    public static function notifyStationNewOrder($phone)
    {
        return self::sendMessage($phone, NotifyProtocol::SMS_TO_STATION_NEW_ORDER);
    }

    public static function sendMessage($phone, $message)
    {
        try {
            if (!is_null($phone)) {
                $result = Sms::make()->to($phone)->content($message)->send();
                return 1;
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return 0;
    }

}
