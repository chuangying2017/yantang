<?php namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;

class StaffPreorder extends Model
{

    protected $guarded = ['id'];

    protected $table = 'staff_preorders';

    public function staff()
    {
        return $this->belongsTo('App\Models\StationStaffs');
    }

    public function preorder()
    {
        return $this->belongsTo('App\Models\Preorder');
    }

    public function station()
    {
        return $this->belongsTo('App\Models\station');
    }

}
