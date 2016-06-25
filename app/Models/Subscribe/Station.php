<?php namespace App\Models\Subscribe;

use App\Models\District;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'station';

    public function preorder()
    {
        return $this->hasMany(Preorder::class);
    }

    public function staffPreorders()
    {
        return $this->hasMany(StaffPreorder::class, 'station_id');
    }

    public function staff()
    {
        return $this->hasMany(StationStaffs::class);
    }

    public function weekly()
    {
        return $this->hasMany(StaffWeekly::class);
    }

    public function preorderOrder()
    {
        return $this->hasManyThrough(PreorderOrder::class, Preorder::class, 'station_id', 'preorder_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
