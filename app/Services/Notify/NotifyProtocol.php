<?php namespace App\Services\Notify;

use App\Services\Notify\Config\NotifyConfigContract;
use App\Services\Notify\Config\NotifyStationNewOrder;
use Toplan\PhpSms\Sms;

class NotifyProtocol {

    const SMS_TO_STATION_NEW_ORDER = '您好，您有一笔新的订单，请及时处理！【燕塘优鲜达】';

    const SMS_TO_CLIENT_PREORDER_IS_ASSIGNED = '亲爱的客户，您的订单已被接受，您可在“个人中心-我的奶卡”查看具体信息；稍后我们会有专人与您联系，谢谢！【燕塘优鲜达】';

    const SMS_TO_STAFF_PREORDER_IS_ASSIGNED = '收到一个新的订单，请及时处理！【燕塘优鲜达】';

    const SMS_TO_ADMIN_PREORDER_IS_ONT_HANDLE_ON_TIME = '您有一个来自服务部的订单待处理，请及时处理！【燕塘优鲜达】';

    const SMS_TO_ADMIN_PREORDER_PREORDER_IS_REJECT = '您有一个来自服务部的订单待处理，请及时处理！【燕塘优鲜达】';


    const NOTIFY_CLIENT_PREORDER_IS_ASSIGNED = 101;

    const NOTIFY_STATION_NEW_ORDER = 201;

    const NOTIFY_ADMIN_PREORDER_IS_ONT_HANDLE_ON_TIME = 301;
    const NOTIFY_ADMIN_PREORDER_PREORDER_IS_REJECT = 302;

    /**
     * @param $action
     * @return NotifyConfigContract|null
     */
    public static function getHandler($action)
    {
        $data = [
            self::NOTIFY_STATION_NEW_ORDER => NotifyStationNewOrder::class
        ];

        return array_get($data, $action, null);
    }


    public static function notifyStationNewOrder($phone)
    {
        return self::sendMessage($phone, NotifyProtocol::SMS_TO_STATION_NEW_ORDER);
    }

    public static function notify($action, $phone = null, $user_id = null, $entity = null)
    {
        $handler = self::getHandler($action);

        if (is_null($handler)) {
            return false;
        }

        if (config('services.notify.sms')) {
            self::sendMessage($phone, $handler::sms());
        }

        if (config('services.notify.weixin')) {
            $handler::weixin($user_id, $entity);
        }

        return true;
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
