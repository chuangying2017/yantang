<?php namespace App\Services\Promotion\Rule\Qualification;

use App\Repositories\Order\ClientOrderRepository;
use App\Services\Order\OrderProtocol;
use App\Services\Promotion\Support\PromotionAbleUserContract;

class FirstPaidOrder implements Qualification {

    /**
     * @var ClientOrderRepository
     */
    private $orderRepo;

    public function __construct(ClientOrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }


    public function check(PromotionAbleUserContract $user, $qualify_values)
    {
        $user_id = $user->getUserId();

        foreach (to_array($qualify_values) as $order_type) {
            if ($this->orderRepo->hasOrdersCount($user_id, $order_type, OrderProtocol::getStatusOfPaid())) {
                return true;
            }
        }

        return false;
    }

}
