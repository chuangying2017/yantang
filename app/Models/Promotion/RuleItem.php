<?php

namespace App\Models\Promotion;

use Illuminate\Database\Eloquent\Model;

class RuleItem extends Model {

    protected $table = 'promotion_item_value';

    protected $guarded = ['id'];
}
