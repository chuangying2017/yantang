<?php namespace App\Repositories\Backend\Station;


interface StationRepositoryContract
{
    /**
     * @param int $user_id
     * @return mixed
     */
    public function getByUserId($user_id);

}