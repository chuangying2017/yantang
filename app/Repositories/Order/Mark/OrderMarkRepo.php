<?php namespace App\Repositories\Order\Mark;

use App\Models\Order\OrderMark;
use App\Services\Order\OrderProtocol;

class OrderMarkRepo {

    public static function removeMark($mark_id)
    {
        return OrderMark::query()->where('id', $mark_id)->delete();
    }

    public static function addMark($order_id, $type, $content)
    {
        if (OrderProtocol::validMark($type)) {
            return OrderMark::create([
                'order_id' => $order_id,
                'mark_type' => $type,
                'mark_content' => $content
            ]);
        }
        return false;
    }

}
