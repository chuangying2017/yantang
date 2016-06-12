<?php namespace App\Repositories\Subscribe\PreorderOrder;

use App\Http\Traits\EloquentRepository;
use App\Models\Subscribe\PreorderOrder;
use App\Services\Subscribe\PreorderProtocol;

class EloquentPreorderOrderRepository implements PreorderOrderRepositoryContract
{
    use EloquentRepository;

    public function moder()
    {
        return 'App\Models\Subscribe\PreorderOrder';
    }

}