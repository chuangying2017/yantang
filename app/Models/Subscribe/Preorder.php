<?php namespace App\Models\Subscribe;

use App\Models\Access\User\User;
use App\Models\Billing\PreorderBilling;
use App\Models\District;
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
        return $this->hasMany(PreorderSku::class);
    }

    public function assign()
    {
        return $this->hasOne(PreorderAssign::class);
    }

    public function billings()
    {
        return $this->hasMany(PreorderBilling::class, 'preorder_id', 'id');
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

}
