<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Baum\Node;

class Category extends Node {

    use SoftDeletes;

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

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function attributes()
    {
        return $this->belongsToMany('App\Models\Attribute', 'category_attribute');
    }

    public function brands()
    {
        return $this->belongsToMany('App\Models\Brand', 'brand_category');
    }
}
