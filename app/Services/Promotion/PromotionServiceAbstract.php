<?php namespace App\Services\Promotion;


use App\Repositories\Promotion\PromotionSupportRepositoryContract;

abstract class PromotionServiceAbstract implements PromotionCheckingContract, PromotionUsedContract, PromotionDispatchContact {

    /**
     * @var PromotionSupportRepositoryContract
     */
    protected $promotionSupportRepo;

    /**
     * PromotionServiceAbstract constructor.
     * @param PromotionSupportRepositoryContract $promotionSupportRepo
     */
    public function __construct(PromotionSupportRepositoryContract $promotionSupportRepo)
    {
        $this->promotionSupportRepo = $promotionSupportRepo;
    }

    protected function canDispatched($user, $rule, $promotion_id)
    {
        return $this->hasQualify($user, $rule) && $this->hasRemain($user['id'], $rule, $promotion_id);
    }

    protected function hasQualify($user, $rule)
    {
        $type = $rule['qualify']['type'];
        switch ($type) {
            case PromotionProtocol::QUALI_TYPE_OF_ALL :
                return true;
            case PromotionProtocol::QUALI_TYPE_OF_LEVEL:
                return in_array($user['level'], $rule['qualify']['values']);
            case PromotionProtocol::QUALI_TYPE_OF_USER:
                return in_array($user['id'], $rule['qualify']['values']);
            default :
                return false;
        }
    }

    protected function hasRemain($user_id, $rule, $promotion_id)
    {
        return $this->promotionSupportRepo->getUserPromotionTimes($promotion_id, $user_id) <= $rule['qualify']['quantity'];
    }


}
