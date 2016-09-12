<?php namespace App\Repositories\Invoice;

use App\Models\Invoice\StationAdminInvoice;
use App\Models\Invoice\StationInvoice;
use App\Models\Invoice\StationInvoiceOrder;
use Illuminate\Database\Eloquent\Model;

class InvoiceProtocol {

    const ID_OF_ADMIN_INVOICE = 0;
    const NAME_OF_ADMIN_INVOICE = '燕塘优先达';

    const PER_PAGE = 10;
    const PER_PAGE_OF_ORDER = 20;

    const INVOICE_STATUS_OF_PENDING = 'pending';
    const INVOICE_STATUS_OF_CONFIRM = 'confirm';
    const INVOICE_STATUS_OF_ERROR = 'error';

    const INVOICE_TYPE_OF_STATION = 1;
    const INVOICE_TYPE_OF_STORE = 2;
    const INVOICE_TYPE_OF_STORE_ADMIN = 3;
    const INVOICE_TYPE_OF_STATION_ADMIN = 4;

    const INVOICE_MODEL_OF_STATION = StationInvoice::class;
    const INVOICE_MODEL_OF_STATION_ADMIN = StationAdminInvoice::class;

    const INVOICE_MODEL_OF_STATION_ORDER = StationInvoiceOrder::class;


	/**
     * @param $merchant
     * @return Model
     */
    public static function getOrderModel($merchant)
    {
        $data = [
            self::INVOICE_MODEL_OF_STATION => self::INVOICE_MODEL_OF_STATION_ORDER
        ];

        return array_get($data, $merchant, null);
    }

    const INVOICE_SERVICE_BASE = 1000;
    const INVOICE_SERVICE_PERCENT = 6;

    public static function calServiceAmount($amount)
    {
        return bcdiv(bcmul($amount, self::INVOICE_SERVICE_PERCENT), self::INVOICE_SERVICE_BASE, 0);
    }


    public static function invoiceHasOrders($type)
    {
        return in_array($type, [self::INVOICE_TYPE_OF_STATION, self::INVOICE_TYPE_OF_STORE]);
    }
}
