<?php namespace App\Repositories\Subscribe\Address;


interface AddressRepositoryContract
{

    /**
     * @param $input
     * @return mixed
     */
    public function create($input);

}