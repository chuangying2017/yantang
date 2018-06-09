<?php namespace App\Services\Notify;

use App\Services\Notify\Content\NotifyClientOrderCommentAlert;
use App\Services\Notify\Content\NotifyClientOrderIsAssign;
use App\Services\Notify\Content\NotifyClientOrderIsEnding;
use App\Services\Notify\Content\NotifyClientTicketIsEnding;
use App\Services\Notify\Content\NotifyContentContract;
use App\Services\Notify\Content\NotifyStaffNewOrder;
use App\Services\Notify\Content\NotifyStationAdminAssignOvertime;
use App\Services\Notify\Content\NotifyStationAdminOrderReject;
use App\Services\Notify\Content\NotifyStationNewOrder;
use App\Services\Notify\Roles\NotifyClient;
use App\Services\Notify\Roles\NotifyRoleContract;
use App\Services\Notify\Roles\NotifyStaff;
use App\Services\Notify\Roles\NotifyStation;
use App\Services\Notify\Roles\NotifyStationAdmin;
use Toplan\PhpSms\Sms;

class NotifyProtocol {

    const CHANNEL_OF_SMS = 'sms';
    const CHANNEL_OF_WEIXIN_TEMPLATE = 'weixin';

    const ROLE_OF_STATION = 'station';
    const ROLE_OF_STAFF = 'staff';
    const ROLE_OF_STATION_ADMIN = 'station_admin';
    const ROLE_OF_CLIENT = 'client';

    const ROLE_OF_COMMENT_ALERT = 'client_comment_alert';


    const NOTIFY_ACTION_CLIENT_PREORDER_IS_ASSIGNED = 101;
    const NOTIFY_ACTION_CLIENT_PREORDER_IS_ENDING = 102;
    const NOTIFY_ACTION_CLIENT_TICKET_IS_ENDING = 111;

    const NOTIFY_ACTION_CLIENT_COMMENT_IS_ALERT = 120;

    const NOTIFY_ACTION_STATION_NEW_ORDER = 201;

    const NOTIFY_ACTION_STAFF_NEW_ORDER = 301;

    const NOTIFY_ACTION_ADMIN_PREORDER_IS_ONT_HANDLE_ON_TIME = 401;
    const NOTIFY_ACTION_ADMIN_PREORDER_PREORDER_IS_REJECT = 402;

    /**
     * @param $action
     * @return NotifyContentContract|null
     * @throws \Exception
     */
    public static function getContentHandler($action)
    {
        $config = [
            self::NOTIFY_ACTION_CLIENT_PREORDER_IS_ASSIGNED => NotifyClientOrderIsAssign::class,

            self::NOTIFY_ACTION_CLIENT_PREORDER_IS_ENDING => NotifyClientOrderIsEnding::class,

            self::NOTIFY_ACTION_CLIENT_TICKET_IS_ENDING => NotifyClientTicketIsEnding::class,

            self::NOTIFY_ACTION_CLIENT_COMMENT_IS_ALERT => NotifyClientOrderCommentAlert::class,

            self::NOTIFY_ACTION_STATION_NEW_ORDER => NotifyStationNewOrder::class,

            self::NOTIFY_ACTION_STAFF_NEW_ORDER => NotifyStaffNewOrder::class,

            self::NOTIFY_ACTION_ADMIN_PREORDER_IS_ONT_HANDLE_ON_TIME => NotifyStationAdminAssignOvertime::class,

            self::NOTIFY_ACTION_ADMIN_PREORDER_PREORDER_IS_REJECT => NotifyStationAdminOrderReject::class,
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
            self::ROLE_OF_STATION => NotifyStation::class,
            self::ROLE_OF_STAFF => NotifyStaff::class,
            self::ROLE_OF_CLIENT => NotifyClient::class,
            self::ROLE_OF_STATION_ADMIN => NotifyStationAdmin::class
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

            case self::NOTIFY_ACTION_CLIENT_TICKET_IS_ENDING:

            case self::NOTIFY_ACTION_CLIENT_PREORDER_IS_ENDING:
                return self::ROLE_OF_CLIENT;

            case self::NOTIFY_ACTION_ADMIN_PREORDER_IS_ONT_HANDLE_ON_TIME:

            case self::NOTIFY_ACTION_ADMIN_PREORDER_PREORDER_IS_REJECT:
                return self::ROLE_OF_STATION_ADMIN;

            case self::NOTIFY_ACTION_STAFF_NEW_ORDER:
                return self::ROLE_OF_STAFF;

            case self::NOTIFY_ACTION_CLIENT_COMMENT_IS_ALERT:
                return self::ROLE_OF_CLIENT;

            default:
                throw new \Exception('通知用户角色不存在');

        }
    }


    public static function notify($id, $action, $channel = null, $entity = null)
    {
        $role = self::getRoleByAction($action);

        $contact_role = self::getRoleHandler($role);

        $content_handler = self::getContentHandler($action);

        try {
            switch ($channel) {
                case NotifyProtocol::CHANNEL_OF_SMS:
                    $phone = $content_handler::getSmsContact($entity) ?: $contact_role::getPhone($id);
                    return self::sendSms($phone, $content_handler::getSmsContent());
                case NotifyProtocol::CHANNEL_OF_WEIXIN_TEMPLATE:
                    return self::sendWeixinTemplate($contact_role::getWeixinOpenId($id), $content_handler, $entity);
                default:
                    if (config('services.notify.sms')) {
                        $phone = $content_handler::getSmsContact($entity) ?: $contact_role::getPhone($id);
                        self::sendSms($phone, $content_handler::getSmsContent());
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
            if (!is_null($phone) && $phone) {
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

        if (!is_array($open_id)) {
            $open_id = [$open_id];
        }
        foreach ($open_id as $single_open_id) {
            try{
                \EasyWeChat::notice()->to($single_open_id)
                    ->url($content::getWeixinTemplateUrl($entity))
                    ->color($content::getWeixinTemplateColor())
                    ->template($content::getWeixinTemplateID())
                    ->andData($content::getWeixinTemplateData($entity))
                    ->send();
            }
            catch( \EasyWeChat\Core\Exceptions\HttpException $e ){
                // errCode 43004: 可能会有已经取消关注的用户，会导致用户下不了单
                if( $e->getCode() != '43004' ){
                    \Log::error( $e );
                }
            }
            catch( \Exception $e ){
                \Log::error($e);
            }
        }
        return true;
    }


}
