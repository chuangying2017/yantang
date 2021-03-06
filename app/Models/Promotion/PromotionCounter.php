<?php

namespace App\Models\Promotion;

use Illuminate\Database\Eloquent\Model;

class PromotionCounter extends Model {

    public $incrementing = false;

    protected $primaryKey = 'promotion_id';

    protected $table = 'promotion_counter';

    protected $guarded = [];

    protected $appends = ['remain'];

    public function getRemainAttribute()
    {
        return $this->attributes['total'] ? $this->attributes['total'] - $this->attributes['dispatch'] : 1;
    }

}
