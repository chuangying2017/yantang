<?php

namespace App\Models\Promotion;

use Illuminate\Database\Eloquent\Model;

class PromotionDetail extends Model {

    protected $primaryKey = 'promotion_id';

    protected $table = 'promotion_info';

    protected $guarded = [];

}
