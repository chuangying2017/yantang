<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:03 PM
 */

namespace App\Services\Image;

use App\Models\Image;
use zgldh\QiniuStorage\QiniuStorage as Qiniu;


/**
 * Class ImageRepository
 * @package App\Services\Image
 */
class ImageRepository
{
    /**
     * @param $callback_url
     * @param $merchant_id
     * @return mixed
     */
    public static function getToken($callback_url, $merchant_id)
    {
        $qiniu = Qiniu::disk('qiniu');
        $callback_body = 'media_id=$(eTag)&filename=$(fname)&image_info=$(imageInfo)&merchant_id=$(x:merchant_id)';
        $policy = [
            'callbackUrl' => $callback_url,
            'callbackBody' => $callback_body,
            'callbackFetchKey' => 1
        ];

        return $qiniu->uploadToken(null, 36000, $policy);

    }

    /**
     * @param $merchant_id
     * @param $media_id
     * @return mixed
     */
    public static function create($merchant_id, $media_id)
    {
        return Image::firstOrCreate([
            'merchant_id' => $merchant_id,
            'media_id' => $media_id,
            'url' => getenv("QINIU_DEFAULT_DOMAIN") . $media_id
        ]);
    }

    /**
     * @param $ids
     */
    public static function delete($ids)
    {
        $image_ids = [];
        if (is_array($ids)) {
            $image_ids = $ids;
        } else {
            $image_ids[] = $ids;
        }
        foreach ($image_ids as $id) {
            Image::destroy($id);
        }
    }



}
