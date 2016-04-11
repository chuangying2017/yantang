<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SectionProduct extends Model {

    use SoftDeletes;

    protected $table = 'section_products';

    protected $guarded = ['id'];

}
