<?php namespace App\Api\V1\Controllers\Admin\Card;

use App\Api\V1\Controllers\Controller;
//use App\Api\V1\Requests\Admin\Preorders\UpdateAssignRequest;
//use App\Repositories\Backend\AccessProtocol;
//use App\Repositories\Preorder\Assign\PreorderAssignRepositoryContract;
//use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Chart\ExcelService;

use Illuminate\Http\Request;
//use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use StaffService;
use DB;

class CardOrderController extends Controller {

    protected $preorderRepo;
    const PER_PAGE = 20;

    /* public function __construct(PreorderRepositoryContract $preorderRepo)
    {
        $this->preorderRepo = $preorderRepo;
    } */

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $order_no = $request->input('order_no') ?: null;
        $pay_order_no = $request->input('pay_order_no') ?: null;
        $phone = $request->input('phone') ?: null;
        
        
        $start_time = $request->input('start_time') ?: null;
        $end_time = $request->input('end_time') ?: null;
        $status = $request->input('status') ?: null;
        
        $where = '';
        if(!is_null($order_no))
        {
            $where .= " and b.order_no = '{$order_no}' ";
        }
        if(!is_null($pay_order_no))
        {
            //$where .= " and b.order_no = '{$order_no}' ";
        }
        if(!is_null($phone))
        {
            $where .= " and d.phone = '{$phone}' ";
        }
        
        if(!is_null($start_time))
        {
            $where .= " and b.created_at >= '{$start_time}' ";
        }
        if(!is_null($end_time))
        {
            $where .= " and b.created_at <= '{$end_time}' ";
        }
        if(!is_null($status))
        {
            $where .= " and e.status = '{$status}' ";
        }
        else 
        {
            if($request->input('status') == '0')
            {
                $where .= " and e.status = '0' ";
            }
        }

        
        $list = DB::select("SELECT d.name, d.phone, d.province, d.city, d.district, d.detail,
            b.id,b.user_id, b.order_no, b.created_at,b.total_amount,b.discount_amount,b.pay_amount,b.pay_status ,e.status
        FROM order_promotions AS a
        JOIN orders b ON a.order_id = b.id
        JOIN promotions AS c ON a.promotion_id = c.id
        JOIN order_address AS d ON b.id = d.order_id
        JOIN tickets AS e ON a.ticket_id = e.id
        WHERE (
        c.id =82
        OR c.id =83
        OR c.id =84
        ){$where} order by b.created_at desc");
        
        
        if ($request->input('export') == 'all') {

            return ExcelService::downCardOrder($list);
        }
        
        return $this->response->array(['data'=>$list]);
    }
    
    
    public function usercard($id)
    {
        
        $order = DB::table("orders")->where(array('id'=>$id))->first();
        
        $address = DB::table("order_address")->where(array('order_id'=>$order->id))->first();
        
        $user_id = $order->user_id;
        
        /* $tickets = DB::select("SELECT b.name, b.desc, c.content, a.created_at,a.end_time, a.status
        FROM `tickets` AS a
        JOIN promotions AS b ON a.promotion_id = b.id
        JOIN order_promotions AS c ON a.id = c.ticket_id
        WHERE user_id = {$user_id}
        AND a.type = 'giftcard'
        LIMIT 0 , 30"); */
        
        $tickets = DB::table('tickets as a')
        ->join('promotions as b','b.id','=','a.promotion_id')
        ->join('order_promotions as c','c.ticket_id','=','a.id')
        ->where('a.user_id',$user_id)
        ->where('a.type','giftcard')
        ->select(DB::raw('b.name, b.desc, c.content, a.created_at,a.end_time, a.status'))
        ->get();
        
        
        return $this->response->array(['data'=>array('user'=>$address,'card'=>$tickets)]);
        
    }
    
    public function detail($id)
    {
        
        $order = DB::table("orders")->where(array('id'=>$id))->first();
        
        $address = DB::table("order_address")->where(array('order_id'=>$order->id))->first();
        
        $user_id = $order->user_id;
        
        $prom = DB::select("SELECT a.content,b.created_at,d.ticket_no,d.status, d.end_time
        FROM order_promotions AS a
        JOIN orders b ON a.order_id = b.id
        JOIN promotions AS c ON a.promotion_id = c.id
        JOIN tickets AS d ON a.ticket_id = d.id
        WHERE (
        c.id =82
        OR c.id =83
        OR c.id =84
        )
        AND b.id ={$id}");
        
        
        $order_skus = DB::select("select name,cover_image,quantity,discount_amount,created_at from order_skus where order_id = {$id}");
        
        return $this->response->array(['data'=>array('user'=>$address,'order'=>$order,'card'=>$prom,'skus'=>$order_skus)]);
    }
    
    
  
    



}
