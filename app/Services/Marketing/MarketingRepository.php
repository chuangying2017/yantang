<?php namespace App\Services\Marketing;

use App\Models\Coupon;
use App\Models\DiscountLimit;
use App\Models\Ticket;
use App\Services\Marketing\Exceptions\CouponValidationException;
use App\Services\Marketing\MarketingProtocol;
use App\Services\Utility\StringUtility;
use Carbon\Carbon;
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
                $coupon->limits = $limits;

                return $coupon;
            });
        } catch (\Exception $e) {
            throw new CouponValidationException('优惠券');
        }
    }

    public static function updateCoupon($coupon_id, $content, $limits)
    {
        $resource_type = MarketingProtocol::TYPE_OF_COUPON;
        try {
            return DB::transaction(function () use ($coupon_id, $content, $limits, $resource_type) {
                $coupon = self::updateCouponContent($coupon_id, $content);
                $limits = self::updateItemLimits($limits, $coupon_id, $resource_type);

                return self::queryFullCoupon($coupon_id);
            });
        } catch (\Exception $e) {
            throw new CouponValidationException('优惠券');
        }
    }


    public static function deleteCoupon($coupon_id)
    {
        $resource_type = MarketingProtocol::TYPE_OF_COUPON;
        try {
            return DB::transaction(function () use ($coupon_id, $resource_type) {

                Coupon::where('id', $coupon_id)->delete();
                DiscountLimit::where('resource_id', $coupon_id)->where('resource_type', $resource_type)->delete();

                return 1;
            });
        } catch (\Exception $e) {
            throw new \Exception('删除优惠券出错');
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
        $query = Coupon::with('limits');
        if ( ! is_null($paginate)) {
            $coupons = $query->paginate($paginate);
        } else {
            $coupons = $query->get();
        }

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

    protected static function storeItemLimits($data, $resource_id, $resource_type)
    {
        return DiscountLimit::create(
            array_merge($data, compact('resource_id', 'resource_type'))
        );
    }

    protected static function updateCouponContent($coupon_id, $data)
    {
        return Coupon::where('id', $coupon_id)->update($data);
    }

    protected static function updateItemLimits($data, $resource_id, $resource_type)
    {
        return DiscountLimit::where(compact('resource_id', 'resource_type'))
            ->update(
                array_merge($data)
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
            while (DB::table('tickets')->where('ticket_no', $ticket_no)->count()) {
                $ticket_no = StringUtility::generateString(MarketingProtocol::LENGTH_OF_TICKET_NO);
            }
        }
        $created_at = Carbon::now();

        return DB::table('tickets')->insertGetId(compact('user_id', 'resource_id', 'resource_type', 'ticket_no', 'status', 'created_at'));
    }

    public static function findTicket($ticket_id)
    {
        return self::queryTicket($ticket_id);
    }

    public static function showTicket($ticket_id)
    {
        return self::queryTicket($ticket_id, ['resource']);
    }

    protected static function queryTickets($user_id, $resource_type, $resource_id, $status = MarketingProtocol::STATUS_OF_PENDING)
    {
        $query = Ticket::where('user_id', $user_id);

        if ( ! is_null($resource_id)) {
            $query = $query->where('resource_id', $resource_id);
        }
        if ( ! is_null($resource_type)) {
            $query = $query->where('resource_type', $resource_type);
        }

        if ( ! is_null($status)) {
            if ($status == MarketingProtocol::STATUS_OF_PENDING) {
                $query = $query->where(function ($query) {
                    $query->whereNull('effect_time')->orWhere('effect_time', '<=', Carbon::now());
                })->where(function ($query) {
                    $query->whereNull('expire_time')->orWhere('expire_time', '>=', Carbon::now());
                });
            }

            $query = $query->where('status', $status);
        }

        return $query->get();
    }

    public static function userTicketsLists($user_id, $status = MarketingProtocol::STATUS_OF_PENDING, $resource_type = null, $resource_id = null)
    {
        $relation = ['resource'];

        $tickets = self::queryTickets($user_id, $resource_type, $resource_id, $status);
        $tickets->load($relation);

        return $tickets;
    }

    public static function userTicketsCount($user_id, $resource_id, $resource_type, $status = MarketingProtocol::STATUS_OF_PENDING)
    {
        return count(self::queryTickets($user_id, $resource_type, $resource_id, $status));
    }

    protected static function queryTicket($ticket_id, $relation = null)
    {
        $key = 'id';

        if ($ticket_id instanceof Ticket) {
            return $ticket_id;
        }

        if (is_array($ticket_id)) {
            return Ticket::whereIn('id', $ticket_id)->get();
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
        return DiscountLimit::where(compact('resource_id', 'resource_type'))->increment('seed_count', $quantity);
    }

    public static function decrementDiscountAmountLimit($resource_id, $resource_type, $quantity = 1)
    {
        return DiscountLimit::where(compact('resource_id', 'resource_type'))->decrement('seed_count', $quantity);
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

    public static function existsDiscountTicket($resource_id, $resource_type)
    {
        return Ticket::where(compact('resource_id', 'resource_type'))->count();
    }


}
