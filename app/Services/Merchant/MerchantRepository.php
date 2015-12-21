<?php namespace App\Services\Merchant;

use App\Models\Merchant;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 2/12/2015
 * Time: 5:30 PM
 */
class MerchantRepository {

    public static function create($merchant_data)
    {
        return Merchant::updateOrCreate(
            ['name' => $merchant_data['name']],
            $merchant_data
        );
    }

    public static function update($merchant_id, $merchant_data)
    {
        $merchant = Merchant::findOrFail($merchant_id);
        $merchant->fill($merchant_data);
        $merchant->save();

        return $merchant;
    }

    public static function delete($merchant_id)
    {
        return Merchant::where('id', $merchant_id)->delete();
    }


}
