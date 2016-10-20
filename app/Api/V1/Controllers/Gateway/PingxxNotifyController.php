<?php

namespace App\Api\V1\Controllers\Gateway;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Requests\Gateway\PingxxNotifyRequest as Request;

use App\Http\Requests;
use App\Services\Pay\Pingxx\PingxxPayService;
use App\Services\Pay\Pingxx\PingxxRefundService;

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

    protected function getEvent($key = null)
    {
        $event = json_decode(file_get_contents("php://input"), true);

        return is_null($key) ? $event : array_get($event, $key);
    }

    protected function getType()
    {
        return $this->getEvent('type');
    }

    protected function getObject()
    {
        return $this->getEvent('data.object');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paid(Request $request)
    {
        $charge = json_decode(json_encode($request->json('data.object')));
//        $charge = $this->getObject();

        if ($this->payService->paid($charge)) {
            return $this->response->accepted();
        }

        $this->response->error('charge not paid', 500);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param PingxxRefundService $refundService
     * @return \Illuminate\Http\Response
     */
    public function refund(Request $request, PingxxRefundService $refundService)
    {
        $refund = json_decode(json_encode($request->json('data.object')));

        if ($refundService->succeed($refund)) {
            return $this->response->accepted();
        }

        $this->response->error('refund fail', 500);
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
