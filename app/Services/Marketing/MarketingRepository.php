<?php namespace App\Services\Marketing;

use App\Models\Coupon;
use App\Models\DiscountLimit;
use App\Models\Ticket;
use App\Services\Marketing\Exceptions\CouponValidationException;
use App\Services\Marketing\MarketingProtocol;
use App\Services\Utility\StringUtility;
use DB;
use Log;


class MarketingRepository {


    /**
     * @param $content
     * @param $limits
     * @return mixed
     */
    public static function storeCoupon($content, $limits)
    {
        $resource_type = MarketingProtocol::TYPE_OF_COUPON;
        try {
            return DB::transaction(function () use ($content, $limits, $resource_type) {
                $coupon = self::storeCouponContent($content);
                $limits = self::storeItemLimits($limits, $coupon['id'], $resource_type);

                return self::queryFullCoupon($coupon['id']);
            });
        } catch (\Exception $e) {
            Log::error($e);
            throw new CouponValidationException('优惠券');
        }
    }

    public static function findCoupon($coupon_id)
    {
        return self::queryCoupon($coupon_id);
    }

    protected static function queryCoupon($coupon_id, $relation = null)
    {
        $coupon = Coupon::findOrFail($coupon_id);
        if ( ! is_null($relation)) {
            $coupon->load($relation);
        }

        return $coupon;
    }

    public static function queryFullCoupon($coupon_id)
    {
        return self::queryCoupon($coupon_id, $relation = ['limits']);
    }

    public static function listsCoupon($status = null, $paginate = null, $relation = null)
    {
        $coupons = Coupon::get();

        return $coupons;
    }

    public static function queryDiscountLimits($resource_id, $resource_type)
    {
        return DiscountLimit::where(compact('resource_id', 'resource_type'))->first();
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
    public static function storeTicket($user_id, $resource_id, $resource_type, $need_no = null, $status = MarketingProtocol::STATUS_OF_PENDING)
    {
        $ticket_no = '';
        if ( ! is_null($need_no)) {
            $ticket_no = StringUtility::generateString(MarketingProtocol::LENGTH_OF_TICKET_NO);
            while (Ticket::where('ticket_no', $ticket_no)->count()) {
                $ticket_no = StringUtility::generateString(MarketingProtocol::LENGTH_OF_TICKET_NO);
            }
        }

        return Ticket::create(compact('user_id', 'resource_id', 'resource_type', 'ticket_no', 'status'));
    }

    public static function findTicket($ticket_id)
    {
        return self::queryTicket($ticket_id);
    }

    public static function showTicket($ticket_id)
    {
        return self::queryTicket($ticket_id, ['resource']);
    }

    protected static function queryTickets($user_id, $resource_id, $resource_type, $status = MarketingProtocol::STATUS_OF_PENDING, $relation = null)
    {
        $paras = [];

        if ( ! is_null($user_id)) {
            $paras['user_id'] = $user_id;
        }
        if ( ! is_null($resource_id)) {
            $paras['resource_id'] = $resource_id;
        }
        if ( ! is_null($resource_type)) {
            $paras['resource_type'] = $resource_type;
        }
        if ( ! is_null($status)) {
            $paras['status'] = $status;
        }

        if ( ! is_null($relation)) {
            return Ticket::with($relation)->where($paras)->get();
        }

        return Ticket::where($paras)->get();
    }

    public static function userTicketsLists($user_id, $status, $resource_type = null, $resource_id = null)
    {
        $relation = ['resource'];

        $tickets = self::queryTickets($user_id, $resource_id, $resource_type, $status, $relation);

        return $tickets;
    }

    public static function userTicketsCount($user_id, $resource_id, $resource_type, $status = MarketingProtocol::STATUS_OF_PENDING)
    {
        return count(self::queryTickets($user_id, $resource_id, $resource_type, $status));
    }

    protected static function queryTicket($ticket_id, $relation = null)
    {
        $key = 'id';

        if ($ticket_id instanceof Ticket) {
            return $ticket_id;
        }

        if (strlen($ticket_id) == MarketingProtocol::LENGTH_OF_TICKET_NO) {
            $key = 'ticket_no';
        }

        $ticket = Ticket::where($key, $ticket_id)->firstOrFail();

        if ( ! is_null($relation)) {
            $ticket->load($relation);
        }

        return $ticket;
    }


    public static function incrementDiscountAmountLimit($resource_id, $resource_type, $quantity = 1)
    {
        return DiscountLimit::where(compact('resource_id', 'resource_type'))->increment('quantity', $quantity);
    }

    public static function decrementDiscountAmountLimit($resource_id, $resource_type, $quantity = 1)
    {
        return DiscountLimit::where(compact('resource_id', 'resource_type'))->decrement('quantity', $quantity);
    }

    public static function updateTicketsStatus($ticket_id, $status)
    {
        $ticket_id = to_array($ticket_id);

        return DB::table('tickets')->whereIn('id', $ticket_id)->update(['status' => $status]);
    }

    public static function setTicketsExpired($ticket_id)
    {
        return self::updateTicketsStatus($ticket_id, MarketingProtocol::STATUS_OF_EXPIRED);
    }


}
