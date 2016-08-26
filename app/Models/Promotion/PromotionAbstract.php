<?php

namespace App\Models\Promotion;

use App\Models\Promotion\Traits\PromotionRelations;
use App\Models\Promotion\Traits\PromotionScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionAbstract extends Model {

    use SoftDeletes, PromotioxnRelations, PromotionScope;

    protected $table = 'promotions';

    protected $guarded = ['id'];

}
