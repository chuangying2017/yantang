<?php namespace App\Services\Marketing;


use App\Services\Marketing\Traits\MarketingItemResource;
use App\Services\Traits\Messages;
use Carbon\Carbon;

abstract class MarketingItemDistributor {

    use Messages, MarketingItemResource;

    const MARKETING_ITEM_CAN_DISTRIBUTE_TO_USER = 1;
    const MARKETING_ITEM_CAN_NOT_DISTRIBUTE_TO_USER = 0;

    protected $is_auth = self::MARKETING_ITEM_CAN_NOT_DISTRIBUTE_TO_USER;


    protected function isAuth($is_auth = null)
    {
        if ( ! is_null($is_auth)) {
            $this->is_auth = $is_auth;

            return $this;
        }

        return $this->is_auth == self::MARKETING_ITEM_CAN_DISTRIBUTE_TO_USER;
    }


    //验证用户是否参加获取优惠
    protected abstract function auth($id, $options);

    //用户获取优惠
    public abstract function send($id, $user_info);

    protected abstract function sendSucceed($id, $user_id);

    //规则验证
    public function filter($resource, $user_info)
    {
        $user_id = array_get($user_info, 'id');
        $user_level = array_get($user_info, 'level', 0);
        $user_role = array_get($user_info, 'role', 0);

        $resource_id = $resource['id'];
        $limits = $resource['limits'];

        if ( ! $this->checkEnable($limits['enable'])) {
            $this->setErrorMessage(trans('marketing.distribute.marketing_item_expired'));

            return 0;
        }

        if ( ! $this->checkEffectTime($limits['expire_time'])) {
            $this->setErrorMessage(trans('marketing.distribute.marketing_item_expired'));

            return 0;
        }

        if ( ! $this->checkQuantity(bcsub($limits['quantity'], $limits['seed_count']))) {
            $this->setErrorMessage(trans('marketing.distribute.stock_out'));

            return 0;
        }


        if ( ! $this->userTakingCountLimit($user_id, $resource_id, $limits['quantity_per_user'])) {
            $this->setErrorMessage(trans('marketing.distribute.user_take_over_quantity'));

            return 0;
        }


        if ( ! $this->checkLevel($user_level, $limits['level'])) {
            $this->setErrorMessage(trans('marketing.distribute.level_not_allow'));

            return 0;
        }


        if ( ! $this->checkRoles($user_role, $limits['roles'])) {
            $this->setErrorMessage(trans('marketing.distribute.roles_not_allow'));

            return 0;
        }

        return 1;
    }

    protected function checkEnable($enable)
    {
        return $enable == MarketingProtocol::DISCOUNT_ENABLE ? 1 : 0;
    }

    protected function checkQuantity($quantity)
    {
        return $quantity > 0 ? 1 : 0;
    }

    protected function checkRoles($user_role, $roles)
    {
        return MarketingProtocol::checkLimit($roles, $user_role);
    }

    protected function checkLevel($user_level, $level)
    {
        return $level > 0 ? $user_level > $level : 1;
    }

    protected function userTakingCountLimit($user_id, $resource_id, $quantity_per_user)
    {
        $count = MarketingRepository::userTicketsCount($user_id, $resource_id, $this->getResourceType(), null);

        //超出领取数量限制
        if ($quantity_per_user <= $count) {
            return false;
        }

        return true;
    }

    protected function checkEffectTime($expire_time)
    {
        return date('Y-m-d H:i:s') < $expire_time;
    }


}
