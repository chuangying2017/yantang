<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use App\Api\V1\Transformers\Mall\ClientOrderTransformer;
use App\Api\V1\Transformers\Subscribe\Station\StaffTransformer;
use App\Api\V1\Transformers\Subscribe\Station\StationTransformer;
use App\Api\V1\Transformers\Traits\SetInclude;
use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\Preorder;

class PreorderTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['skus', 'station', 'staff', 'deliver', 'assign', 'order'];

    public function transform(Preorder $preorder)
    {
        $this->setInclude($preorder);

        $data = [
            'id' => $preorder->id,
            'name' => $preorder->name,
            'user' => ['id' => $preorder->user_id],
            'order_no' => $preorder->order_no,
            'phone' => $preorder->phone,
            'address' => $preorder->address,
            'station' => ['id' => $preorder->station_id],
            'staff' => ['id' => $preorder->staff_id],
//            'district' => ['id' => $preorder->district_id],
            'status' => $preorder->status,
            'start_time' => $preorder->start_time,
            'created_at' => $preorder->created_at,
        ];

        return $data;
    }

    public function includeOrder(Preorder $preorder)
    {
        return $this->item($preorder->order, new ClientOrderTransformer(), true);
    }

    public function includeSkus(Preorder $preorder)
    {
        return $this->collection($preorder->skus, new PreorderSkuTransformer(), true);
    }

    public function includeBillings(Preorder $preorder)
    {
        return $this->collection($preorder->billings, new PreorderBillingTransformer(), true);
    }

    public function includeDeliver(Preorder $preorder)
    {
        return $this->collection($preorder->deliver, new PreorderDeliverTransformer(), true);
    }

    public function includeStaff(Preorder $preorder)
    {
        return $this->item($preorder->staff, new StaffTransformer(), true);
    }

    public function includeStation(Preorder $preorder)
    {
        return $this->item($preorder->station, new StationTransformer(), true);
    }

    public function includeAssign(Preorder $preorder)
    {
        return $this->item($preorder->assign, new PreorderAssignTransformer(), true);
    }

}
