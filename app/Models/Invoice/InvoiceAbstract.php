<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;

class InvoiceAbstract extends Model {

    protected $table = 'invoices';

    protected $primaryKey = 'invoice_no';

    public $incrementing = false;

    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany(StationInvoiceOrder::class, 'invoice_no', 'invoice_no');
    }
    

}
