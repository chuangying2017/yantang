<?php

namespace App\Models\Statement;

use App\Services\Statement\StatementProtocol;
use Illuminate\Database\Eloquent\Model;

class StatementAbstract extends Model {

    protected $table = 'statements';
    protected $guarded = [];
    protected $primaryKey = 'statement_no';
    public $incrementing = false;

    public function products()
    {
        return $this->hasMany(StatementProduct::class, 'statement_no', 'statement_no');
    }

}
