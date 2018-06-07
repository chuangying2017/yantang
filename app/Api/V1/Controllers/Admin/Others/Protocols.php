<?php

namespace App\Api\V1\Controllers\Admin\Others;

use App\Models\Monitors;
use App\Models\Product\Category;
use App\Models\Promotion\Activity;
use App\models\Protocol;
use Cache;
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
        //sku_keys
        $result = Cache::get('getSkus',null);
        dd($result->getSkus()->toArray());
    }

    //setting default value

    public function setting($setting_id, Request $request){

        return $this->response->array($this->SetClass->updateSet($setting_id,$request->all()));

    }

    //展示
    public function show($id){
        return $this->response->array($this->SetClass->getSetting($id));
    }

    public function cache_check_temp_order(){
        $result = Cache::get('item_keys',null);
        dd($result);
    }
}
