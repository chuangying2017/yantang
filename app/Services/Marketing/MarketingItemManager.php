<?php namespace App\Services\Marketing;

use App\Services\Marketing\Traits\MarketingFilter;
use App\Services\Marketing\Traits\MarketingItemResource;

abstract class MarketingItemManager implements MarketingInterface {

    use MarketingFilter, MarketingItemResource;


    //创建优惠项
    public abstract function create($input);

    //增加优惠数量
    public static function add($id, $quantity = 1)
    {

    }

    //删除优惠项
    public function delete($id)
    {
        #todo call repo
        if ( ! MarketingRepository::existsDiscountTicket($id, $this->getResourceType())) {
            if($this->getResourceType() == MarketingProtocol::TYPE_OF_COUPON) {
                MarketingRepository::deleteCoupon($id);
            }

            return true;
        }

        throw new \Exception('优惠已生效,无法删除');
    }

    //启用
    public static function enable($id)
    {
        #todo call repo

    }

    //禁用
    public static function disable($id)
    {
        #todo call repo
    }


}
