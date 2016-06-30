<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use App\Api\V1\Transformers\Subscribe\Station\StaffTransformer;
use App\Api\V1\Transformers\Subscribe\Station\StationTransformer;
use App\Api\V1\Transformers\Traits\SetInclude;
use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\Preorder;

class PreorderTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['station', 'staff', 'billings', 'assign'];

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
            'district' => ['id' => $preorder->district_id],
            'status' => $preorder->status,
            'charge_status' => $preorder->charge_status,
            'start_time' => $preorder->start_time,
            'end_time' => $preorder->end_time,
            'created_at' => $preorder->created_at,
        ];

        if ($preorder->relationLoaded('skus')) {
            $data['skus'] = $this->transformSkus($preorder);
        }

        return $data;
    }

    public function transformSkus(Preorder $preorder)
    {
        $data = [];

        foreach ($preorder->skus as $sku) {
            $data[intval($sku['weekday'])][intval($sku['daytime'])][] =
                ['weekday' => $sku['weekday'],
                    'daytime' => $sku['daytime'],
                    'product_sku_id' => $sku['product_sku_id'],
                    'name' => $sku['name'],
                    'cover_image' => $sku['cover_image'],
                    'quantity' => $sku['quantity'],
                    'price' => display_price($sku['price']),
                ];
        }

        for ($weekday = 1; $weekday <= 7; $weekday++) {
            for ($daytime = 0; $daytime <= 1; $daytime++) {
                if ($weekday == 7) {
                    $full[0][$daytime] = array_get($data, 0 . '.' . $daytime, []);
                } else {
                    $full[$weekday][$daytime] = array_get($data, $weekday . '.' . $daytime, []);
                }
            }
        }

        return $full;
    }

    public function includeBillings(Preorder $preorder)
    {
        return $this->collection($preorder->billings, new PreorderBillingTransformer(), true);
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
