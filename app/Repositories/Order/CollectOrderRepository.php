<?php namespace App\Repositories\Order;

use App\Repositories\Station\Staff\EloquentStaffRepository;
use App\Models\Collect\CollectOrder;

class CollectOrderRepository implements ClientOrderRepositoryContract{
    protected $lists_relations = ['sku','address'];

    /**
     * @var StaffRepositoryContract
     */
    private $staffRepo;

    /**
     * StationController constructor.
     * @param StaffRepositoryContract $staffRepo
     */
    public function __construct(EloquentStaffRepository $staffRepo)
    {
        $this->staffRepo = $staffRepo;
    }

    /**
     * @param $data
     */
    public function createOrder($data)
    {
        $order = CollectOrder::createOrder($data);

        return $order;
    }

    public function getPaginatedOrders($status = null, $order_by = 'created_at', $sort = 'desc', $per_page = CollectOrderProtocol::ORDER_PER_PAGE)
    {
        $query = CollectOrder::query();
        if (!is_null($status)) {
            $query = $query->where('status', $status);
        }
        return $query->with($this->lists_relations)->orderBy($order_by, $sort)->paginate($per_page);
    }



    public function updateOrderStatus($order_id, $status){

    }

    public function updateOrderPayStatus($order_id, $status, $pay_channel = null){

    }

    public function updateOrderStatusAsPaid($order_id, $pay_channel){

    }

    public function updateOrderStatusAsDeliver($order_id){

    }

    public function updateOrderStatusAsDeliverDone($order_id){

    }

    public function updateOrderStatusAsDone($order_id){

    }

    public function updateOrderStatusAsCancel($order_id){

    }

    public function getOrder($order_no, $with_detail = false){

    }

    public function getAllOrders($status = null, $order_by = 'created_at', $sort = 'desc'){

    }

    public function getPaidOrders( $station_id = null, $start_time = null, $end_time = null, $staff_id = null )
    {
        $cOrders = CollectOrder::query();
        if( $station_id ){
            $staffs = $this->staffRepo->getAll( $station_id, null, true );
            $staffIds = $staffs->pluck('id')->filter();
            $cOrders->whereIn( 'staff_id', $staffIds );
        }
        if( $staff_id ){
            $cOrders->where('staff_id', $staff_id);
        }

        if( $start_time ){
            $cOrders->where('pay_at', '>', $start_time);
        }
        if( $end_time ){
            $cOrders->where('pay_at', '<', $end_time);
        }


        return $cOrders->get();
    }
}
