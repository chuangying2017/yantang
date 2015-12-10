<?php namespace App\Http\Transformers;

use App\Models\Image;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract {

    public function transform(Image $image)
    {
        return [
            'id'       => (int)$image->id,
            'url'      => image_url($image->media_id),
            'media_id' => $image->media_id
        ];
    }

}
