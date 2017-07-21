<?php namespace App\Api\V1\Transformers\Promotion;

use App\Api\V1\Transformers\Promotion\CouponTransformer;
use App\Api\V1\Transformers\Traits\SetInclude;
use App\Models\Promotion\Giftcard;
use League\Fractal\TransformerAbstract;

class GiftcardTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['coupon'];

    public function transform(Giftcard $giftcard)
    {
        $this->setInclude($giftcard);
        return $giftcard->toArray();
    }

    public function includeCoupon(Giftcard $giftcard)
    {
        return $this->item($giftcard['coupon'], new CouponTransformer(), true);
    }
}
