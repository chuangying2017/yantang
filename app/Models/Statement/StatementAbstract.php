<?php

namespace App\Models\Statement;

use App\Models\Store\StatementProduct;
use App\Services\Statement\StatementProtocol;
use Illuminate\Database\Eloquent\Model;

class StatementAbstract extends Model {

    protected $table = 'statements';
    protected $guarded = [];
    protected $primaryKey = 'statement_no';

    protected function products()
    {
        return $this->hasMany(StatementProduct::class, 'statement_no', 'statement_no');
    }

}
