<?php namespace App\Repositories\Order\Address;

use App\Models\Order\OrderAddress;
use App\Repositories\Address\AddressRepositoryContract;

class EloquentOrderAddressRepository {

    /**
     * @var AddressRepositoryContract
     */
    private $addressRepo;

    /**
     * EloquentAddressRepository constructor.
     * @param AddressRepositoryContract $addressRepo
     */
    public function __construct(AddressRepositoryContract $addressRepo)
    {
        $this->addressRepo = $addressRepo;
    }

    public function createOrderAddress($order_id, $address_id)
    {
        if (!$address_id) {
            return null;
        }
        $address = $this->addressRepo->getAddress($address_id);
        $order_address = OrderAddress::firstOrNew(['order_id' => $order_id]);
        $order_address->fill(
            array_only($address->toArray(), [
                'name',
                'phone',
                'province',
                'city',
                'district',
                'detail',
                'zip',
            ])
        );

        $order_address->save();
        return $order_address;
    }

    public function getOrderAddress($order_id)
    {
        return OrderAddress::find($order_id);
    }

}
