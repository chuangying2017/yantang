<?php namespace App\Services\Marketing;

use App\Services\Marketing\Traits\MarketingFilter;
use App\Services\Marketing\Traits\MarketingItemResource;

abstract class MarketingItemManager {

    use MarketingFilter, MarketingItemResource;

    protected $resource_type;

    //创建优惠项
    public abstract function create($input);

    //增加优惠项
    public static function add($id, $quantity = 1)
    {

    }

    //删除优惠项
    public static function delete($id)
    {
        #todo call repo
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
