<?php

namespace App\Models\RedEnvelope;

use Illuminate\Database\Eloquent\Model;

class RedEnvelopeRecord extends Model {

    protected $table = 'red_records';

    protected $guarded = [];

    public function rule()
    {
        return $this->belongsTo(RedEnvelopeRule::class, 'rule_id', 'id');
    }

    public function receivers()
    {
        return $this->hasMany(RedEnvelopeReceive::class, 'record_id', 'id');
    }

}
