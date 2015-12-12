<?php namespace App\Services\Client;


class AddressService {

    const DEFAULT_ROLE = 3;
    const PRIMARY_ROLE = 1;
    const RECENT_USE_ROLE = 2;

    /**
     * @param $data
     * @return mixed
     */
    public static function create($data)
    {
        $address_data = self::encodeAddressInputData($data);

        return AddressRepository::create($address_data);
    }

    protected static function encodeAddressInputData($data)
    {
        $address_data['user_id'] = $data['user_id'];
        $address_data['name'] = $data['name'];
        $address_data['mobile'] = $data['phone'];
        $address_data['tel'] = isset($data['tel']) ? $data['tel'] : '';
        $address_data['province'] = $data['province'];
        $address_data['city'] = $data['city'];
        $address_data['zip'] = isset($data['zip']) ? $data['zip'] : 0;
        $address_data['district'] = isset($data['district']) ? $data['district'] : '';
        $address_data['detail'] = $data['detail'];
        $address_data['role'] = isset($data['role']) ? $data['role'] : self::DEFAULT_ROLE;
        $address_data['display_name'] = isset($data['display_name'])
            ? $data['display_name']
            : $address_data['province'] . $address_data['city'] . $address_data['detail'];

        return $address_data;
    }


    public static function show($address_id)
    {
        return AddressRepository::show($address_id);
    }

    public static function orderAddress($address_id)
    {
        return self::show($address_id);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public static function update($id, $data)
    {
        $data = self::encodeAddressInputData($data);

        return AddressRepository::update($id, $data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function delete($id)
    {
        return AddressRepository::delete($id);
    }

    /**
     * get all addresses by user
     * @param $user_id
     * @return mixed
     */
    public static function fetchByUser($user_id)
    {
        return AddressRepository::lists($user_id);
    }
}
