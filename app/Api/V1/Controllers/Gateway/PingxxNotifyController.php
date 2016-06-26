<?php

namespace App\Api\V1\Controllers\Gateway;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Requests\Gateway\PingxxNotifyRequest as Request;

use App\Http\Requests;
use App\Services\Pay\Pingxx\PingxxPayService;

class PingxxNotifyController extends Controller {

    /**
     * @var PingxxPayService
     */
    private $payService;

    /**
     * PingxxNotifyController constructor.
     * @param PingxxPayService $payService
     */
    public function __construct(PingxxPayService $payService)
    {
        $this->payService = $payService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paid(Request $request)
    {
        $charge = json_decode(json_encode($request->input()));

        if ($this->payService->paid($charge)) {
            return $this->response->accepted();
        }

        $this->response->error('charge not paid', 500);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function refund(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function transfer(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function summary(Request $request)
    {
        //
    }


}
