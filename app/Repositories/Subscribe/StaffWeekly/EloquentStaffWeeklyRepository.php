<?php namespace App\Repositories\Subscribe\StaffWeekly;

use App\Models\Subscribe\StaffPreorder;
use App\Http\Traits\EloquentRepository;


class EloquentStaffWeeklyRepository implements StaffWeeklyRepositoryContract
{
    use EloquentRepository;

    public function moder()
    {
        return 'App\Models\Subscribe\StaffWeekly';
    }

}