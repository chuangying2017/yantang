<?php namespace App\Repositories\Image;

use App\Models\Image;
use Storage;

class QiniuImageRepository implements ImageRepositoryContract {

    /**
     * @param $callback_url
     * @param $merchant_id
     * @return mixed
     */
    public function getToken($callback_url = null, $merchant_id = null)
    {
        $callback_url = is_null($callback_url) ? api_route('qiniu.callback') : $callback_url;
        $qiniu = Storage::disk('qiniu');
        $callback_body = 'media_id=$(etag)&filename=$(fname)&imageinfo=$(imageInfo)';
        if ($merchant_id) {
            $callback_body .= '&merchant_id=' . $merchant_id;
        }

        $policy = [
            'callbackUrl' => $callback_url,
            'callbackBody' => $callback_body,
            'callbackFetchKey' => 1
        ];

        return $qiniu->uploadToken(null, 3600 * 24, $policy);
    }

    /**
     * @param $merchant_id
     * @param $media_id
     * @return mixed
     */
    public function create($data)
    {
        $image_data = array_only($data, ['media_id', 'filename', 'imageinfo', 'url']);
        $image_data['url'] = config('filesystems.disks.qiniu.domains.custom') . $image_data['media_id'];

        return Image::create($image_data);
    }

    public function getAllPaginated()
    {
        return Image::paginate(ImageProtocol::IMAGE_PER_PAGE);
    }

    public function getImages($image_ids)
    {
        return Image::find($image_ids);
    }

    /**
     * @param $ids
     */
    public function delete($images_ids)
    {
        return Image::destroy($images_ids);
    }

    public function getAll()
    {
        return Image::get();
    }
}
