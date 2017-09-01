<?php namespace App\Models\Subscribe;

use App\Events\Preorder\AssignIsCreate;
use App\Models\Access\User\User;
use App\Models\Billing\OrderBilling;
use App\Models\Billing\PreorderBilling;
use App\Models\District;
use App\Models\Residence;
use App\Models\Order\Order;
use App\Models\Promotion\Ticket;
use App\Models\RedEnvelope\RedEnvelopeRecord;
use App\Services\Order\OrderProtocol;
use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preorder extends Model {

    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'preorders';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skus()
    {
        return $this->hasMany(PreorderSku::class, 'order_id', 'order_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function assign()
    {
        return $this->hasOne(PreorderAssign::class);
    }

    public function deliver()
    {
        return $this->hasMany(PreorderDeliver::class, 'preorder_id', 'id');
    }

    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id', 'id');
    }

    public function staff()
    {
        return $this->belongsTo(StationStaff::class, 'staff_id', 'id');
    }

    public function district()
    {
        return $this->hasMany(District::class);
    }

    public function counter()
    {
        return $this->hasMany(PreorderSkuCounter::class, 'preorder_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'source_id', 'order_id');
    }

    public function redEnvelope()
    {
        return $this->hasOne(RedEnvelopeRecord::class, 'resource_id', 'order_id');
    }

    public function billings()
    {
        return $this->hasMany(OrderBilling::class, 'order_id', 'order_id');
    }

    public function residence()
    {
        return $this->belongsTo(Residence::class, 'residence_id', 'id');
    }

    //操作
    public function changeStation($station_id)
    {
        if ($this->isAssigning()) {
            $this->assign()->delete();
            $this->addAssign($station_id);
        } else if ($this->isConfirm()) {
            $this->assign()->delete();
            $this->addAssign($station_id, '接错重派');
            $this->fill([
                'station_id' => $station_id,
                'staff_id' => 0,
                'status' => PreorderProtocol::ORDER_STATUS_OF_ASSIGNING
            ]);

            if (!$this->isInvoice()) {
                $this->attributes['confirm_at'] = null;
            }
            try {
                if ($order = $this->order) {
                    if ($order['pay_status'] == OrderProtocol::PAID_STATUS_OF_PAID && $order['status'] != OrderProtocol::ORDER_IS_PAID && in_array($order['refund_status'], [OrderProtocol::REFUND_STATUS_OF_DEFAULT, OrderProtocol::REFUND_STATUS_OF_APPLY])) {
                        $order['status'] = OrderProtocol::ORDER_IS_PAID;
                        $order->save();
                    }
                }
            } catch (\Exception $e) {
                \Log::error($e);
            }

            $this->save();
        }
    }

    public function isAssigning()
    {
        return in_array($this->attributes['status'], [
            PreorderProtocol::ORDER_STATUS_OF_ASSIGNING,
        ]);
    }

    public function isConfirm()
    {
        return in_array($this->attributes['status'], [
            PreorderProtocol::ORDER_STATUS_OF_SHIPPING,
            PreorderProtocol::ORDER_STATUS_OF_DONE,
        ]);
    }

    public function isInvoice()
    {
        return $this->attributes['invoice'];
    }

    public function addAssign($station_id, $memo = '')
    {
        $assign = $this->assign()->create([
            'station_id' => $station_id,
            'status' => PreorderProtocol::ASSIGN_STATUS_OF_UNTREATED,
            'time_before' => Carbon::now()->addHours(PreorderProtocol::HOURS_OF_ASSIGN_DISPOSE_HOURS),
            'memo' => $memo
        ]);

        event(new AssignIsCreate($assign));
    }

}
