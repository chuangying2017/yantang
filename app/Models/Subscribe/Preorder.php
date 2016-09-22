<?php namespace App\Models\Subscribe;

use App\Models\Access\User\User;
use App\Models\Billing\PreorderBilling;
use App\Models\District;
use App\Models\Order\Order;
use App\Models\Promotion\Ticket;
use App\Models\RedEnvelope\RedEnvelopeRecord;
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

}
