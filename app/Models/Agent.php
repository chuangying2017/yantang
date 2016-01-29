<?php

namespace App\Models;

use App\Models\Access\User\User;
use Baum\Node;
use Illuminate\Database\Eloquent\Model;

class Agent extends Node {

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

    public function promotions()
    {
        return $this->morphToMany(Promotion::class, 'agent');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }


}
