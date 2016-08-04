<?php

namespace App\Models\Promotion;

use App\Services\Promotion\PromotionProtocol;

class Campaign extends PromotionAbstract {

    const TYPE_OF_PROMOTION = PromotionProtocol::TYPE_OF_SPECIAL_CAMPAIGN;

}
