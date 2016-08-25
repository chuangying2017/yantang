<?php namespace App\Models\Billing;

use App\Models\Subscribe\PreorderSku;
use Illuminate\Database\Eloquent\Model;

class PreorderBilling extends Model {

    protected $guarded = ['id'];

    protected $table = 'preorder_billings';

    public function skus()
    {
        return $this->belongsToMany(PreorderSku::class, 'billing_preorder_skus', 'preorder_billing_id', 'preorder_sku_id');
    }


}
