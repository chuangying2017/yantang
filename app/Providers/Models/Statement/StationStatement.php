<?php namespace App\Models\Statement;

use App\Models\Subscribe\Station;
use App\Services\Statement\StatementProtocol;
use Illuminate\Database\Eloquent\Builder;

class StationStatement extends StatementAbstract {

    protected $attributes = [
        'type' => StatementProtocol::TYPE_OF_STATION
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', StatementProtocol::TYPE_OF_STATION);
        });
    }

    public function station()
    {
        return $this->belongsTo(Station::class, 'merchant_id', 'id');
    }
}
