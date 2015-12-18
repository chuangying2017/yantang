<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model {

    use SoftDeletes;

    protected $table = 'sections';

    protected $guarded = ['id'];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_section');
    }

}
