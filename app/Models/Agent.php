<?php

namespace App\Models;

use Baum\Node;
use Illuminate\Database\Eloquent\Model;

class Agent extends Node
{
    protected $table = 'agents';

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
