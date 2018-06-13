<?php

namespace App\Api\V1\Controllers\Admin\Others;

use App\Models\Comment;
use App\Models\Monitors;
use App\Models\Product\Category;
use App\Models\Promotion\Activity;
use App\models\Protocol;
use App\Models\Subscribe\Preorder;
use App\Repositories\Comment\CommentProtocol;
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
     * */
    public function cache_check(){

        $preorders = Comment::query()->where('comment_type',CommentProtocol::COMMENT_STATUS_IS_USES)->whereHas('preorders',function($query){
            $query->where('staff_id',683);
        })->get();
        dd($preorders);

        /*$da =\Cache::get('commentLed',null);

        foreach ($da as $value){
            dd($value->updated_at->toDateTimeString());
        }*/

        /*
       $time = Carbon::parse('2018-06-13')->timestamp;

       $time1 = Carbon::parse('2018-06-14')->timestamp;

       dd(86400 / 3600);*/

        /*
              //  $result=Preorder::find(60502);
               // dump($result->user->id);
                //$result->user->wallet->increment('integral',100);
                //dd(Carbon::now()->addDays(-3)->toDateString());
        /*        $da[0]->score = 3;
                $da[0]->save();
               echo $da[0]->id;*/
    }
}
