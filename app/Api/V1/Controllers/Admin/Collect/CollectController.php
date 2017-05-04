<?php namespace App\Api\V1\Controllers\Admin\Collect;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\CollectOrderTransformer;
use App\Repositories\Order\CollectClientOrderRepository;
use App\Services\Order\OrderProtocol;
use App\Services\Order\CollectOrderProtocol;
use App\Models\Collect\CollectOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CollectController extends Controller {
 /**
     * @var CollectClientOrderRepository
     */
    private $collectRepo;

    /**
     * CollectController constructor.
     * @param DistrictRepositoryContract $districtRepo
     */
    public function __construct(CollectClientOrderRepository $collectRepo)
    {
        $this->collectRepo = $collectRepo;
    }

    public function index(Request $request)
    {
        $status = $request->input('status');
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);


        $orders = CollectOrder::query()
                ->with(['order']);

        if( $status == 'collected'){
            $orders->whereNotNull('pay_at');
        }
        else{
            $orders->whereNull('pay_at');
        }

        if( $month ){
            if( $month > Carbon::now()->month ){
                $year -= 1;
            }
            $startTime = Carbon::parse($year.'-'.$month);
            $endTime = $startTime->addMonth();
        }
        $orders->whereBetween( 'created_at', [$startTime, $endTime] );
        $orders = $orders->paginate(CollectOrderProtocol::ORDER_PER_PAGE);

        return $this->response->paginator($orders, new CollectOrderTransformer());
    }

    public function show(CollectOrder $collect_order)
    {
        return $this->response->item($collect_order, new CollectOrderTransformer());
    }

}
