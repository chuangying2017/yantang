<?php namespace App\Repositories\Address;
interface AddressRepositoryContract {

    public function getAddress($address_id);

    public function getAllAddress();

    public function addAddress($data);

    public function updateAddress($address_id, $data);

    public function deleteAddress($address_id);

    public function getPrimaryAddress();

    public function setPrimaryAddress($address_id);

}
