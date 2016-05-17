<?php

namespace App\Models\Product;

use App\Models\Product\Traits\CategoryRelation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Baum\Node;

class Category extends Node {

    use SoftDeletes, CategoryRelation;

    // 'parent_id' column name
    protected $parentColumn = 'pid';

    // 'lft' column name
    protected $leftColumn = 'lid';

    // 'rgt' column name
    protected $rightColumn = 'rid';

    // 'depth' column name
    protected $depthColumn = 'depth';

    // guard attributes from mass-assignment
    protected $guarded = array('id', 'pid', 'lid', 'rid', 'depth');



}
