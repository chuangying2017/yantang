<?php namespace App\Api\V1\Transformers;

use App\Models\Image;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract {

    public function transform(Image $image)
    {
        return [
            'media_id' => $image['media_id'],
            'filename' => $image['filename'],
            'imageinfo' => json_decode($image['imageinfo'], true),
            'url' => $image['url']
        ];
    }

}
