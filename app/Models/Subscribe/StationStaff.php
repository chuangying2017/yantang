<?php namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StationStaff extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'station_staffs';

    public function preorders()
    {
        return $this->hasMany(Preorder::class, 'staff_id', 'id');
    }

}
