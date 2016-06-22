<?php namespace App\Services\Order\Generator;

use App\Repositories\Address\AddressRepositoryContract;

class GetOrderAddress extends GenerateHandlerAbstract {

    /**
     * @var AddressRepositoryContract
     */
    private $addressRepo;


    /**
     * GetOrderAddress constructor.
     * @param AddressRepositoryContract $addressRepo
     */
    public function __construct(AddressRepositoryContract $addressRepo)
    {
        $this->addressRepo = $addressRepo;
    }

    public function handle(TempOrder $temp_order)
    {
        $temp_order->setAddress(
            $this->addressRepo->getAddress($temp_order->getAddress())
        );

        return $this->next($temp_order);
    }
}
