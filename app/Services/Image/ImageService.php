<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:40 AM
 */

namespace App\Services\Image;


use App\Models\Merchant;

class ImageService
{

    public static function getToken()
    {
        return ImageRepository::getToken($callback_url, $merchat_id);
    }

    /**
     *
     * @param $data
     */
    public static function callback($data)
    {
        //todo@bryant handle callback
    }

    /**
     * @param $ids array|integer
     */
    public static function delete($ids)
    {
        return ImageRepository::delete($ids);
    }

    public static function getByMerchant($merchant_id)
    {
        $merchant = Merchant::findOrFail($merchant_id);

        return $merchant->images()->get();
    }


}
