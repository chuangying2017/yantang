<?php namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;

class StaffPreorder extends Model
{

    protected $guarded = ['id'];

    protected $table = 'staff_preorders';

    public function staff()
    {
        return $this->belongsTo('App\Models\Subscribe\StationStaffs', 'staff_id');
    }

    public function preorder()
    {
        return $this->belongsTo('App\Models\Subscribe\Preorder');
    }

    public function station()
    {
        return $this->belongsTo('App\Models\Subscribe\station');
    }

}
