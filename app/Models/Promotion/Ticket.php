<?php namespace App\Models\Promotion;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {

    protected $table = 'tickets';

    protected $guarded = ['id'];

    /*************************************************************
     * Relations
     **************************************************************/

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

}
