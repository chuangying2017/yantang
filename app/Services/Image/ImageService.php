<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 10:40 AM
 */

namespace App\Services\Image;


use App\Models\Merchant;
use App\Services\ApiConst;

class ImageService {

    public static function getToken($merchant_id)
    {
        $callback_url = api_route('qiniu.callback');

        return ImageRepository::getToken($callback_url, $merchant_id);
    }

    public static function create($data)
    {
        return ImageRepository::create($data);
    }

    /**
     * @param $ids array|integer
     */
    public static function delete($ids, $merchant_id)
    {
        ImageRepository::delete($ids, $merchant_id);
    }

    public static function getByMerchant($merchant_id)
    {
        $merchant = Merchant::findOrFail($merchant_id);

        return $merchant->images()->orderBy('created_at', 'desc')->paginate(ApiConst::IMAGE_PER_PAGE);
    }


}
