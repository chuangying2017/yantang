<?php
/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 23/11/2015
 * Time: 4:03 PM
 */

namespace App\Services\Image;

use App\Models\Image;
use Storage;


/**
 * Class ImageRepository
 * @package App\Services\Image
 */
class ImageRepository {

    /**
     * @param $callback_url
     * @param $merchant_id
     * @return mixed
     */
    public static function getToken($callback_url, $merchant_id)
    {
        $qiniu = Storage::disk('qiniu');
        $callback_body = 'hash=$(eTag)&media_id=$(eTag)&filename=$(fname)&imageinfo=$(imageInfo)&merchant_id=' . $merchant_id;
        $policy = [
            'callbackUrl'      => $callback_url,
            'callbackBody'     => $callback_body,
            'callbackFetchKey' => 1
        ];


        return $qiniu->uploadToken(null, 36000, $policy);

    }

    /**
     * @param $merchant_id
     * @param $media_id
     * @return mixed
     */
    public static function create($data)
    {
        $image_data = array_only($data, ['merchant_id', 'media_id', 'filename', 'imageinfo']);

        return Image::create($image_data);
    }

    /**
     * @param $ids
     */
    public static function delete($images_id, $merchant_id)
    {
        $images_id = to_array($images_id);

        Image::whereIn('id', $images_id)->where('merchant_id', $merchant_id)->delete();
    }


}
