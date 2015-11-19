<?php namespace App\Services\Marketing;

use App\Models\Coupon;
use App\Models\DiscountLimit;
use App\Models\Ticket;
use App\Services\Marketing\Exceptions\CouponValidationException;
use App\Services\Marketing\MarketingProtocol;
use App\Services\Utility\StringUtility;
use DB;


class MarketingRepository {


    /**
     * @param $content
     * @param $limits
     * @return mixed
     */
    public static function storeCoupon($content, $limits)
    {
        $resource_type = MarketingProtocol::TYPE_OF_COUPON;
        try
        {
            return DB::transaction(function () use ($content, $limits, $resource_type) {
                $coupon = self::storeCouponContent($content);
                $limits = self::storeItemLimits($limits, $coupon['id'], $resource_type);
                $coupon['limits'] = $limits;
                return $coupon;
            });
        }
        catch (\Exception $e)
        {
            throw new CouponValidationException('优惠券');
        }
    }


    protected static function storeCouponContent($data)
    {
        return Coupon::create($data);
    }

    /**
     * @param $data
     * @param $resource_id
     * @param $resource_type
     * @return DiscountLimit instance
     */
    protected static function storeItemLimits($data, $resource_id, $resource_type)
    {
        return DiscountLimit::create(
            array_merge($data, compact('resource_id', 'resource_type'))
        );
    }


    /**
     * @param $user_id
     * @param $coupon_id
     * @return Ticket;
     */
    public static function storeTicket($user_id, $coupon_id)
    {
        $ticket_no = StringUtility::generateString(10);
        while( Ticket::where('ticket_no', $ticket_no)->count() )
        {
            $ticket_no = StringUtility::generateString(10);
        }

        $status = MarketingProtocol::STATUS_OF_PENDING;
        return Ticket::create(compact('user_id', 'coupon_id', 'ticket_no', 'status'));
    }


}
