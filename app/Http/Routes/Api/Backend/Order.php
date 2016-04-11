<?php

$api->get('deliver/company', [
    'as'   => 'express.company',
    'uses' => 'OrderDeliverController@company'
]);

$api->post('deliver/{order_no}', [
    'as'   => 'order.deliver',
    'uses' => 'OrderDeliverController@deliver'
]);

$api->delete('deliver/{order_no}', [
    'as'   => 'order.cancel.deliver',
    'uses' => 'OrderDeliverController@cancel'
]);

$api->post('orders/refund/{refund_order_id}/done', [
    'as'   => 'orders.refund.done',
    'uses' => 'OrderRefundController@done'
]);

$api->resource('orders/refund', 'OrderRefundController');

$api->resource('orders', 'OrderController');
