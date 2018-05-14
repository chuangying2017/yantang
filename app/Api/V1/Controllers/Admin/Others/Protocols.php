<?php

namespace App\Api\V1\Controllers\Admin\Others;

use App\Models\Monitors;
use App\models\Protocol;

use App\Repositories\Other\ProtocolGenerator;
use Dingo\Api\Facade\Route;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Protocols extends Controller
{
    use Helpers;

    protected $ProtocolRepositoryContact;
    //show protocol data
    public function index(){
        return $this->ProtocolRepositoryContact->getAllProtocol();
    }
    //
    public function protocoledit(Request $request){

        $data = $request->all();

        $result = $this->ProtocolRepositoryContact->updateProtocol(['type'=>$data['type']],$data);

        return response()->json(submitStatus($result));
    }

    public function __construct(ProtocolGenerator $protocolGenerator)
    {
        $this->ProtocolRepositoryContact = $protocolGenerator;
    }

    /**
     * this is my test file
     * hope others peoples not action
     *
     * */
    public function testfile(){
      //  dd('11111');
       // $current = Route::current()->uri();
        // dd($current);
        $result = Monitors::create(['action'=>'ndndo']);
        dd($result);
    }
}
