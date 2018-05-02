<?php namespace App\Api\V1\Controllers\Admin\Card;

use App\Api\V1\Controllers\Controller;
//use App\Api\V1\Requests\Admin\Preorders\UpdateAssignRequest;
//use App\Repositories\Backend\AccessProtocol;
//use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
//use App\Repositories\Preorder\PreorderRepositoryContract;
//use App\Services\Chart\ExcelService;

use Illuminate\Http\Request;
//use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use StaffService;
use DB;

class CategoriesController extends Controller {

    protected $preorderRepo;
    const PER_PAGE = 20;

    /* public function __construct(PreorderRepositoryContract $preorderRepo)
    {
        $this->preorderRepo = $preorderRepo;
    } */

    public function test()
    {
        //echo 'testcard';
        
        DB::connection()->enableQueryLog();
        $list = DB::table('order_promotions')
        ->join('orders','orders.id','=','order_promotions.order_id')
        ->join('order_address','order_address.order_id','=','orders.id')
        ->join('promotions','promotions.id','=','order_promotions.promotion_id')
        ->where('promotions.id','82')
        ->orWhere('promotions.id','83')
        ->orWhere('promotions.id','84')
        ->select('order_no')
        ->get();
        
        
        print_r(DB::getQueryLog());
        
        die;
        
        $categories = DB::connection('mysql_card')->table('categories')->where(array('id'=>2))->get();
        
        print_r($categories);die;
        
        $categories = DB::connection('mysql_card')->select('select * from categories');
        return response()->json([
            'data' => $categories,
            'state' => 'CA'
        ]);
    }
    
    
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $categories = DB::connection('mysql_card')->select('select * from covers where id > 3');
        
        return $this->response->array(['data'=>$categories]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        echo 'create';
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $data = array(
            'name' => $request->input('name'),
            'pic_url' => $request->input('pic_url'),
            'created_at' => date('Y-m-d H:i:s',time()),
        );
        $result = DB::connection('mysql_card')->table('categories')->insert($data);
        
        $insert_id = DB::insertGetId();
        
        $categories = DB::connection('mysql_card')->table('categories')->where(array('id'=>1))->first();
        
        $data = array(
            'cover_ids' => $categories->cover_ids.','.$insert_id,
        );
        
        $result = DB::connection('mysql_card')->table('categories')->where(array('id'=>1))->update($data);
        
        return $result > 0 ? response()->json(['data' => 'success','state' => $result]) : 0;
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo 'show';
        //
        
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $categories = DB::connection('mysql_card')->table('covers')->where(array('id'=>$id))->first();

        return $this->response->array(['data'=>$categories]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        
        $id =  $request->input('id');
        $name = $request->input('name');
        $pic_url = $request->input('pic_url');
        
        $data = array(
            'name' => $request->input('name'),
            'pic_url' => $request->input('pic_url'),
            'updated_at' => date('Y-m-d H:i:s',time()),
        );
        
        $result = DB::connection('mysql_card')->table('covers')->where(array('id'=>$id))->update($data);
        
        return $result > 0 ? response()->json(['data' => 'success','state' => $result]) : 0;
        
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $result = DB::connection('mysql_card')->table('covers')->where(array('id'=>$id))->delete();
        
        return response()->json(['data' => $result,'state' => '1']);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function index_(Request $request)
    {
        $categories = DB::connection('mysql_card')->select('select * from categories');
        
        return $this->response->array(['data'=>$categories]);
        
        
        
        //echo 'cateindex';
        /* $order_no = $request->input('order_no') ?: null;
        $pay_order_no = $request->input('pay_order_no') ?: null;
        $phone = $request->input('phone') ?: null;
        $start_time = $request->input('start_time') ?: null;
        $end_time = $request->input('end_time') ?: null;
        $status = $request->input('status') ?: null;
        $station_id = $request->input('station_id') ? explode(',', $request->input('station_id')) : null;
        $residence_id = $request->input('residence_id') ? explode(',', $request->input('residence_id')) : null;
        $time_name = $request->input('time_name', 'created_at');

        
        if ($request->input('export') == 'all') {
            $orders = $this->preorderRepo->getAll($station_id, $order_no, $pay_order_no, $phone, $status, $start_time, $end_time, $time_name, null, $residence_id);
            
            echo count($orders);die;
            return ExcelService::downPreorder($orders);
        }

        $orders = $this->preorderRepo->getAllPaginated($station_id, $order_no, $pay_order_no, $phone, $status, $start_time, $end_time, $time_name, null, $residence_id);
        $orders->load('assign', 'station');

        return $this->response->paginator($orders, new PreorderTransformer()); */
    }

    public function show_($id)
    {
       /*  $order = $this->preorderRepo->get($order_id, true);

        return $this->response->item($order, new PreorderTransformer()); */
    }

    public function update_(UpdateAssignRequest $request, $order_id, PreorderAssignRepositoryContract $assignRepo)
    {
        /* $station_id = $request->input('station');

        $order = $this->preorderRepo->get($order_id, false);

        if ($order->isConfirm() && !access()->hasRole(AccessProtocol::ID_ROLE_OF_SUPERVISOR)) {
            return $this->response->array(['data' => $order->assign]);
        }

        $order->changeStation($station_id);

        return $this->response->array(['data' => $order->assign]); */
    }


}
