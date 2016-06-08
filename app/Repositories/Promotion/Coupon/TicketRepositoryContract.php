<?php namespace App\Repositories\Promotion\Coupon;

use App\Models\Promotion\Coupon;

interface TicketRepositoryContract {

    public function createTicket($user_id = null, Coupon $coupon, $generate_no = false);

    public function updateTicket($ticket_id, $status);

    public function deleteTicket($ticket_id);

    public function getTicket($ticket_id, $with_coupon = true);

    public function getTicketsByCoupon($coupon_id, $with_coupon = true);

    public function getTicketsByUser($user_id, $with_coupon = true);

}
