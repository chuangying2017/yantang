<?php namespace App\Services\Promotion\Rule\Qualification;

use App\Repositories\Order\CollectOrderRepository;
use App\Services\Order\OrderProtocol;
use App\Services\Promotion\Support\PromotionAbleUserContract;

class QualifyCollectOrder implements Qualification {

    /**
     * @var CollectOrderRepository
     */
    private $orderRepo;

    public function __construct(CollectOrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }


    public function check(PromotionAbleUserContract $user, $start_time)
    {
        $user_id = $user->getUserId();

        if ($this->orderRepo->getFirstPaidOrder($user_id, OrderProtocol::ORDER_TYPE_OF_SUBSCRIBE, array_first($start_time))) {
            return true;
        }

        return false;
    }

}
