<?php namespace App\Repositories\Subscribe\StaffPreorder;

use App\Models\Subscribe\StaffPreorder;
use App\Http\Traits\EloquentRepository;


class EloquentStaffPreorderRepository implements StaffPreorderRepositoryContract
{
    use EloquentRepository;

    public function moder()
    {
        return 'App\Models\Subscribe\StaffPreorder';
    }

    public function updateByPreorderId($input, $preorder_id)
    {
        $query = StaffPreorder::where('preorder_id', $preorder_id)->fill($input);
        $query->save();
        return $query;
    }
}