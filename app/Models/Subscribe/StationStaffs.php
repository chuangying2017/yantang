<?php namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StationStaffs extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'station_staffs';

    public function preorders()
    {
        return $this->belongsToMany(Preorder::class, 'staff_preorders', 'staff_id', 'preorder_id')->withPivot('index', 'id')->orderBy('pivot_index', 'asc');
    }

    public function preorderOrders()
    {
        return $this->hasMany(PreorderOrder::class, 'staff_id');
    }
}
