<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Statement extends Model {

    protected $table = 'store_statements';

    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(StatementProduct::class, 'statement_no', 'statement_no');
    }
}
