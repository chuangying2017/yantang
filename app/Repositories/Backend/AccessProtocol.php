<?php namespace App\Repositories\Backend;
class AccessProtocol {

    const USER_STATUS_OF_OK = 1;
    const USER_STATUS_OF_DEACTIVATED = 0;
    const USER_STATUS_OF_BANDED = 2;

    const ROLE_OF_SUPERVISOR = 'Supervisor';
    const ROLE_OF_CLIENT = 'Client';
    const ROLE_OF_STATION = 'Station';
    const ROLE_OF_STORE = 'Store';
    const ROLE_OF_STAFF = 'Staff';
    const ROLE_OF_STATION_ADMIN = 'StationAdmin';
    const ROLE_OF_STORE_ADMIN = 'StoreAdmin';
    const ROLE_OF_MALL = 'Mall';
    const ROLE_OF_FINANCE = 'Finance';
    const ROLE_OF_USER = 'User';

    const ID_ROLE_OF_SUPERVISOR = 1;
    const ID_ROLE_OF_CLIENT = 2;
    const ID_ROLE_OF_STATION = 3;
    const ID_ROLE_OF_STORE = 4;
    const ID_ROLE_OF_STAFF = 5;
    const ID_ROLE_OF_STATION_ADMIN = 6;
    const ID_ROLE_OF_STORE_ADMIN = 7;
    const ID_ROLE_OF_MALL = 8;
    const ID_ROLE_OF_FINANCE = 9;
    const ID_ROLE_OF_USER = 10;


    public static function roles()
    {
        return [
            self::ID_ROLE_OF_SUPERVISOR => self::ROLE_OF_SUPERVISOR,
            self::ID_ROLE_OF_CLIENT => self::ROLE_OF_CLIENT,
            self::ID_ROLE_OF_STATION => self::ROLE_OF_STATION,
            self::ID_ROLE_OF_STORE => self::ROLE_OF_STORE,
            self::ID_ROLE_OF_STAFF => self::ROLE_OF_STAFF,
            self::ID_ROLE_OF_STATION_ADMIN => self::ROLE_OF_STATION_ADMIN,
            self::ID_ROLE_OF_STORE_ADMIN => self::ROLE_OF_STORE_ADMIN,
            self::ID_ROLE_OF_MALL => self::ROLE_OF_MALL,
            self::ID_ROLE_OF_FINANCE => self::ROLE_OF_FINANCE,
            self::ID_ROLE_OF_USER => self::ROLE_OF_USER
        ];
    }

}
