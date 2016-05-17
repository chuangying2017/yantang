<?php namespace App\Repositories\Backend\Station;

use App\Models\Subscribe\Station;
use Pheanstalk\Exception;

class EloquentStationRepository implements StationRepositoryContract
{
    public function getByUserId($user_id)
    {
        return Station::where('user_id', $user_id)->get();
    }
}