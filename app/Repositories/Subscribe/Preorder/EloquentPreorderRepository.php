<?php namespace App\Repositories\Subscribe\Preorder;

use App\Models\Subscribe\Preorder;

class EloquentPreorderRepository implements PreorderRepositoryContract
{

    public function moder()
    {
        return 'App\Models\Subscribe\Address';
    }

    public function create($input)
    {
        return Preorder::create($input);
    }

}