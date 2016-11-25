<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use App\Api\V1\Transformers\Mall\ClientOrderTransformer;
use App\Api\V1\Transformers\Promotion\RedEnvelopeRecordTransformer;
use App\Api\V1\Transformers\Promotion\TicketTransformer;
use App\Api\V1\Transformers\Subscribe\Station\StaffTransformer;
use App\Api\V1\Transformers\Subscribe\Station\StationTransformer;
use App\Api\V1\Transformers\Traits\SetInclude;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\Preorder;

class PreorderTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['skus', 'station', 'staff', 'deliver', 'assign', 'order', 'tickets', 'redEnvelope'];

    public function transform(Preorder $preorder)
    {
        $this->setInclude($preorder);

        $data = [
            'id' => $preorder->id,
            'name' => $preorder->name,
            'user' => ['id' => $preorder->user_id],
            'order_no' => $preorder->order_no,
            'phone' => $preorder->phone,
            'street' => $preorder->street,
            'address' => $preorder->address,
            'station' => ['id' => $preorder->station_id],
            'staff' => ['id' => $preorder->staff_id],
            'status' => $preorder->status,
            'start_time' => $preorder->start_time,
            'end_time' => $preorder->end_time,
            'daytime' => $preorder->daytime,
            'weekday_type' => $preorder->weekday_type,
            'pause_time' => $preorder->pause_time,
            'restart_time' => $preorder->restart_time,
            'created_at' => $preorder->created_at,
            'pause_status' => $this->isPause($preorder),
            'is_new' => $preorder->start_time >= date('Y-m-d') ? 1 : 0,
            'has_coupon' => 0,
        ];

        return $data;
    }

    protected function isPause($preorder)
    {
        if (!is_null($preorder['pause_time']) && $preorder['pause_time'] <= Carbon::today()) {
            if (is_null($preorder['restart_time']) || $preorder['restart_time'] > Carbon::today()) {
                return true;
            }
        }

        return false;
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
        if($preorder->station) {
            return $this->item($preorder->station, new StationTransformer(false), true);
        }
        return null;
    }

    public function includeAssign(Preorder $preorder)
    {
        if ($preorder->assign) {
            return $this->item($preorder->assign, new PreorderAssignTransformer(), true);
        }

        return null;
    }

    public function includeTickets(Preorder $preorder)
    {
        if ($preorder->tickets) {
            return $this->collection($preorder->tickets, new TicketTransformer(), true);
        }

        return null;
    }

    public function includeRedEnvelope(Preorder $preorder)
    {
        if ($preorder->redEnvelope) {
            return $this->item($preorder->redEnvelope, new RedEnvelopeRecordTransformer(), true);
        }

        return null;
    }


}
