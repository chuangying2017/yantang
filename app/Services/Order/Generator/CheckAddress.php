<?php namespace App\Services\Order\Generator;

use App\Repositories\Auth\User\UserContract;
use App\Services\Preorder\PreorderAssignServiceContact;
use App\Services\Promotion\Support\PromotionAbleUserContract;
use App\Repositories\Address\AddressRepositoryContract;

class CheckAddress extends GenerateHandlerAbstract {

    /**
     * @var PreorderAssignServiceContact
     */
    private $assignService;

    /**
     * @var AddressRepositoryContract
     */
    private $addressRepo;

    /**
     * CheckAddress constructor.
     * @param PreorderAssignServiceContact $assignService
     */
    public function __construct(PreorderAssignServiceContact $assignService, AddressRepositoryContract $addressRepo)
    {
        $this->assignService = $assignService;
        $this->addressRepo = $addressRepo;
    }

    public function handle(TempOrder $temp_order)
    {
        $address_id = $temp_order->getAddress();
        $address = $this->addressRepo->getAddress($address_id);

        $station = $this->assignService->assign($address['info']['longitude'], $address['info']['latitude'], $address['info']['district']);

        if (!$station) {
            $temp_order->setError('该地址暂时未支持配送');
        }

        return $this->next($temp_order);
    }

}
