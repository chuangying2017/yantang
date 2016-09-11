<?php namespace App\Repositories\Invoice;
class InvoiceProtocol {
    
    const PER_PAGE = 10;

    const INVOICE_STATUS_OF_PENDING = 'pending';
    const INVOICE_STATUS_OF_CONFIRM = 'confirm';
    const INVOICE_STATUS_OF_ERROR = 'error';

    const INVOICE_TYPE_OF_STATION = 1;

    const INVOICE_SERVICE_BASE = 1000;
    const INVOICE_SERVICE_PERCENT = 6;

    public static function calServiceAmount($amount)
    {
        return bcdiv(bcmul($amount, self::INVOICE_SERVICE_PERCENT), self::INVOICE_SERVICE_BASE, 0);
    }

}
