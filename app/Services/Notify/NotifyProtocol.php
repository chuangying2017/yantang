<?php namespace App\Services\Notify;

use App\Services\Notify\Content\NotifyContentContract;
use App\Services\Notify\Content\NotifyStationNewOrder;
use App\Services\Notify\Roles\NotifyRoleContract;
use App\Services\Notify\Roles\NotifyStation;
use Toplan\PhpSms\Sms;

class NotifyProtocol {

    const CHANNEL_OF_SMS = 'sms';
    const CHANNEL_OF_WEIXIN_TEMPLATE = 'weixin';

    const ROLE_OF_STATION = 'station';
    const ROLE_OF_STATION_ADMIN = 'station_admin';
    const ROLE_OF_CLIENT = 'client';


    const SMS_TO_STATION_NEW_ORDER = '您好，您有一笔新的订单，请及时处理！【燕塘优鲜达】';

    const SMS_TO_CLIENT_PREORDER_IS_ASSIGNED = '亲爱的客户，您的订单已被接受，您可在“个人中心-我的奶卡”查看具体信息；稍后我们会有专人与您联系，谢谢！【燕塘优鲜达】';

    const SMS_TO_STAFF_PREORDER_IS_ASSIGNED = '收到一个新的订单，请及时处理！【燕塘优鲜达】';

    const SMS_TO_ADMIN_PREORDER_IS_ONT_HANDLE_ON_TIME = '您有一个来自服务部的订单待处理，请及时处理！【燕塘优鲜达】';

    const SMS_TO_ADMIN_PREORDER_PREORDER_IS_REJECT = '您有一个来自服务部的订单待处理，请及时处理！【燕塘优鲜达】';


    const NOTIFY_ACTION_CLIENT_PREORDER_IS_ASSIGNED = 101;

    const NOTIFY_ACTION_STATION_NEW_ORDER = 201;

    const NOTIFY_ACTION_ADMIN_PREORDER_IS_ONT_HANDLE_ON_TIME = 301;
    const NOTIFY_ACTION_ADMIN_PREORDER_PREORDER_IS_REJECT = 302;


    /**
     * @param $action
     * @return NotifyContentContract|null
     * @throws \Exception
     */
    public static function getContentHandler($action)
    {
        $config = [
            self::NOTIFY_ACTION_STATION_NEW_ORDER => NotifyStationNewOrder::class
        ];

        $handler = array_get($config, $action, null);

        if (!$handler) {
            throw new \Exception('错误的通知内容类型');
        }

        return $handler;
    }

    /**
     * @param $role
     * @return NotifyRoleContract
     * @throws \Exception
     */
    public static function getRoleHandler($role)
    {
        $config = [
            self::ROLE_OF_STATION => NotifyStation::class
        ];

        $handler = array_get($config, $role, null);
        if (!$handler) {
            throw new \Exception('错误的联系对象类型');
        }

        return $handler;
    }

    public static function getContactHandler($role, $id, $channel)
    {
        $handler = self::getRoleHandler($role);
        switch ($channel) {
            case NotifyProtocol::CHANNEL_OF_SMS:
                return $handler::getPhone($id);
            case NotifyProtocol::CHANNEL_OF_WEIXIN_TEMPLATE:
                return $handler::getWeixinOpenId($id);
            default:
                throw new \Exception('通知渠道不存在');
        }
    }

    public static function getRoleByAction($action)
    {
        switch ($action) {
            case self::NOTIFY_ACTION_STATION_NEW_ORDER:
                return self::ROLE_OF_STATION;
            case self::NOTIFY_ACTION_CLIENT_PREORDER_IS_ASSIGNED:
                return self::ROLE_OF_CLIENT;
            case self::NOTIFY_ACTION_ADMIN_PREORDER_IS_ONT_HANDLE_ON_TIME:
            case self::NOTIFY_ACTION_ADMIN_PREORDER_PREORDER_IS_REJECT:
                return self::ROLE_OF_STATION_ADMIN;
            default:
                throw new \Exception('通知用户角色不存在');
        }
    }

    public static function notifyStationNewOrder($phone)
    {
        return self::sendSms($phone, NotifyProtocol::SMS_TO_STATION_NEW_ORDER);
    }


    public static function notify($id, $action, $channel = null, $entity = null)
    {
        try {
            $role = self::getRoleByAction($action);

            $contact_role = self::getRoleHandler($role);
            $content_handler = self::getContentHandler($action);

            switch ($channel) {
                case NotifyProtocol::CHANNEL_OF_SMS:
                    return self::sendSms($contact_role::getPhone($id), $content_handler::getSmsContent());
                case NotifyProtocol::CHANNEL_OF_WEIXIN_TEMPLATE:
                    return self::sendWeixinTemplate($contact_role::getWeixinOpenId($id), $content_handler, $entity);
                default:
                    if (config('services.notify.sms')) {
                        self::sendSms($contact_role::getPhone($id), $content_handler::getSmsContent());
                    }
                    if (config('services.notify.weixin')) {
                        self::sendWeixinTemplate($contact_role::getWeixinOpenId($id), $content_handler, $entity);
                    }
                    return true;
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return false;
    }

    public static function sendSms($phone, $message)
    {
        try {
            if (!is_null($phone)) {
                return Sms::make()->to($phone)->content($message)->send();
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return 0;
    }

    /**
     * @param $openid
     * @param NotifyContentContract $content
     * @param $entity
     * @return mixed
     */
    public static function sendWeixinTemplate($open_id, $content, $entity)
    {
        if (!$open_id) {
            return false;
        }
        
        if (is_array($open_id)) {
            foreach ($open_id as $single_open_id) {
                \EasyWeChat::notice()->to($single_open_id)
                    ->url($content::getWeixinTemplateUrl($entity))
                    ->color($content::getWeixinTemplateColor())
                    ->template($content::getWeixinTemplateID())
                    ->andData($content::getWeixinTemplateData($entity))
                    ->send();
            }
            return true;
        }

        return \EasyWeChat::notice()->to($open_id)
            ->url($content::getWeixinTemplateUrl($entity))
            ->color($content::getWeixinTemplateColor())
            ->template($content::getWeixinTemplateID())
            ->andData($content::getWeixinTemplateData($entity))
            ->send();
    }


}
