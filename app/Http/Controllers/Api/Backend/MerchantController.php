<?php

namespace App\Http\Controllers\Api\Backend;

use App\Services\Merchant\MerchantService;
use App\Http\Requests\MerchantRequest as Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MerchantController extends Controller {

    /**
     * @var MerchantService
     */
    private $merchantService;

    /**
     * MerchantController constructor.
     * @param MerchantService $merchantService
     */
    public function __construct(MerchantService $merchantService)
    {
        $this->merchantService = $merchantService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $merchant = $this->merchantService->create($input);
        } catch(\Exception $e) {
//            return $e->getTrace();
            $this->response->errorInternal($e->getMessage());
        }


        return $this->response->created()->setContent(['data' => $merchant]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
