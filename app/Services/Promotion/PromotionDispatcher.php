<?php namespace App\Services\Promotion;

use App\Services\Promotion\Support\PromotionAbleUserContract;

interface PromotionDispatcher {

    public function dispatch(PromotionAbleUserContract $user, $promotion_id, $source_type = PromotionProtocol::TICKET_RESOURCE_OF_USER, $source_id = 0);

    public function dispatchWithoutCheck($user_id, $promotion_id, $source_type = PromotionProtocol::TICKET_RESOURCE_OF_ADMIN, $source_id = 0);

    
}
