<?php

namespace App\Api\V1\Controllers\Admin\Others;

use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use App\Api\V1\Transformers\Subscribe\Station\StationTransformer;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Monitors;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Product\ProductInfo;
use App\Models\Product\ProductSku;
use App\Models\Promotion\Activity;
use App\models\Protocol;
use App\Models\Subscribe\Preorder;
use App\Models\Subscribe\Station;
use App\Repositories\Comment\CommentProtocol;
use App\Repositories\Integral\OrderFacade;
use App\Repositories\Station\StationProtocol;
use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Log;
use App\Models\Settings;
use App\Repositories\Other\ProtocolGenerator;
use App\Repositories\setting\SetMode;
use Dingo\Api\Facade\Route;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Protocols extends Controller
{

    protected $ProtocolRepositoryContact;

    protected $SetClass;

    //show protocol data
    public function index(){
        try{
            return $this->ProtocolRepositoryContact->getAllProtocol();
        }catch (\Exception $exception){
            dd($exception->getMessage());
        }

    }
    //
    public function protocoledit(Request $request){

        $data = $request->all();

        $result = $this->ProtocolRepositoryContact->updateProtocol(['type'=>$data['type']],$data);

        return response()->json(submitStatus($result));
    }

    public function __construct(ProtocolGenerator $protocolGenerator, SetMode $setMode)
    {
        $this->ProtocolRepositoryContact = $protocolGenerator;
        $this->SetClass = $setMode;
    }

    /**
     * this is my test file
     * hope others peoples not action
     *
     * */
    public function testfile(){
         /*   $array = ['id'=>1,'key'=>'set','value'=>['interval_time'=>'5','active'=>'not active']];
            dd(Settings::create($array));*/
       echo phpinfo();

    }

    //setting default value

    public function setting($setting_id, Request $request){

        return $this->response->array($this->SetClass->updateSet($setting_id,$request->all()));

    }

    //展示
    public function show($id){
        return $this->response->array($this->SetClass->getSetting($id));
    }

    /*
     * cache check
     * http://o7tep4eu1.bkt.clouddn.com
     * */
    public function cache_check(){

      /*  $cats = Category::query()->get();
        dd($cats->load('products'));*/
   /*       $image = ProductInfo::where('detail','<>',null)->get();
        try {
            foreach ($image as $key=>$value)
            {
               // $value->cover_image=str_replace('http://yt.cdn.weazm.com/',env('QINIU_DOMAIN'),$value->cover_image);
                if(strpos($value->detail,'http://') === false){
                        continue;
                }
                $value->detail =  substr($value->detail,0,strpos($value->detail,'http://')). env('QINIU_DOMAIN') . substr($value->detail,strpos($value->detail,'m/') + 2);
                $value->save();
            }
        } catch (\ErrorException $e) {
            dd($e->getMessage());
        }
        echo 'success';*/
    }

    //同时更新多个记录，参数，表名，数组（别忘了在一开始use DB;）
    public function updateBatch($tableName = "", $multipleData = array()){

        if( $tableName && !empty($multipleData) ) {

            // column or fields to update
            $updateColumn = array_keys($multipleData[0]);
            $referenceColumn = $updateColumn[0]; //e.g id
            unset($updateColumn[0]);
            $whereIn = "";

            $q = "UPDATE ".$tableName." SET ";
            foreach ( $updateColumn as $uColumn ) {
                $q .=  $uColumn." = CASE ";

                foreach( $multipleData as $data ) {
                    $q .= "WHEN ".$referenceColumn." = ".$data[$referenceColumn]." THEN '".$data[$uColumn]."' ";
                }
                $q .= "ELSE ".$uColumn." END, ";
            }
            foreach( $multipleData as $data ) {
                $whereIn .= "'".$data[$referenceColumn]."', ";
            }
            $q = rtrim($q, ", ")." WHERE ".$referenceColumn." IN (".  rtrim($whereIn, ', ').")";
            // Update
            return DB::update(DB::raw($q));

        } else {
            return false;
        }

    }


}

/*       $image = ProductInfo::where('detail','<>',null)->get();
     try {
         foreach ($image as $key=>$value)
         {
            // $value->cover_image=str_replace('http://yt.cdn.weazm.com/',env('QINIU_DOMAIN'),$value->cover_image);
             if(strpos($value->detail,'http://') === false){
                     continue;
             }
             $value->detail =  substr($value->detail,0,strpos($value->detail,'http://')). env('QINIU_DOMAIN') . substr($value->detail,strpos($value->detail,'m/') + 2);
             $value->save();
         }
     } catch (\ErrorException $e) {
         dd($e->getMessage());
     }
     echo 'success';*/