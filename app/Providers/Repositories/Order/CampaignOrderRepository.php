<?php namespace App\Repositories\Order;

use App\Models\Order\Order;
use App\Services\Order\OrderProtocol;

class CampaignOrderRepository extends ClientOrderRepository {

    protected $detail_relations = ['skus', 'billings', 'special', 'memo'];
    protected $lists_relations = ['skus', 'special'];


    /**
     * @param $data
     */
    public function createOrder($data)
    {
        $order = parent::createOrder($data);
        $order->special = $this->attachOrderSpecialCampaign($order, $data['special_campaign']);

        return $order;
    }


    private function attachOrderSpecialCampaign(Order $order, $special_campaign)
    {
        if (is_null($special_campaign)) {
            return $order;
        }
        return $order->special()->create(
            array_only($special_campaign, ['campaign_id', 'campaign_name', 'campaign_cover_image'])
        );
    }


    protected function setOrderType()
    {
        $this->type = OrderProtocol::ORDER_TYPE_OF_CAMPAIGN;
    }

    public function getAllOrders($status = null, $order_by = 'created_at', $sort = 'desc')
    {
        $query = Order::query()->where('order_type', $this->type);
        if (!is_null($status)) {
            $query = $query->where('status', $status);
        }
        return $query->with('skus', 'special')->orderBy($order_by, $sort)->get();
    }

    public function getPaginatedOrders($status = null, $order_by = 'created_at', $sort = 'desc', $per_page = OrderProtocol::ORDER_PER_PAGE)
    {
        $query = Order::query()->where('order_type', $this->type);
        if (!is_null($status)) {
            $query = $query->where('status', $status);
        }
        return $query->with('skus', 'special')->orderBy($order_by, $sort)->paginate($per_page);
    }

}
