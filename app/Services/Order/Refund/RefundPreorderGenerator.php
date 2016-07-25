<?php namespace App\Services\Order\Refund;

use App\Repositories\Preorder\EloquentPreorderRepository;
use App\Repositories\Preorder\Product\PreorderSkusRepository;
use App\Services\Order\OrderGenerator;

class RefundPreorderGenerator {

    /**
     * @var OrderGenerator
     */
    private $orderGenerator;


    /**
     * @var EloquentPreorderRepository
     */
    private $preorderRepo;

    /**
     * RefundPreorderGenerator constructor.
     * @param OrderGenerator $orderGenerator
     * @param EloquentPreorderRepository $preorderRepo
     */
    public function __construct(OrderGenerator $orderGenerator, EloquentPreorderRepository $preorderRepo)
    {
        $this->orderGenerator = $orderGenerator;
        $this->preorderRepo = $preorderRepo;
    }

    public function refund($order_id)
    {
        $preorder = $this->preorderRepo->get($order_id);

        //未开始直接退款
        
        //暂停中

        //配送中未暂停、 已完成

    }


}
