<?php namespace App\Repositories\Subscribe\Preorder;

use App\Models\Subscribe\Preorder;

class EloquentPreorderRepository implements PreorderRepositoryContract
{

    public function moder()
    {
        return 'App\Models\Subscribe\Preorder';
    }

    public function create($input)
    {
        $input['address'] = $input['area'] . $input['address'];
        unset($input['area']);
        $input['order_no'] = uniqid('pre_');
        //是否需要充值 0:不是 1:是
        $input['charge_status'] = 1;
        return Preorder::create($input);
    }

    public function byUserId($user_id)
    {
        return Preorder::where('user_id', $user_id)->first();
    }
}