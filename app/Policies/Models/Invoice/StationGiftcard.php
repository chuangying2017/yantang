<?php namespace App\Models\Invoice;
use App\Repositories\Invoice\InvoiceProtocol;
use Illuminate\Database\Eloquent\Builder;

class StationGiftcard extends InvoiceAbstract {

    protected $attributes = [
        'type' => InvoiceProtocol::INVOICE_TYPE_OF_GIFTCARD
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', InvoiceProtocol::INVOICE_TYPE_OF_GIFTCARD);
        });
    }



}
