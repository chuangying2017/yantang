<?php

namespace App\Http\Controllers\Api\Backend;

use App\Services\Orders\OrderDeliver;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderDeliverController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = OrderDeliver::expressCompany();

        return $this->response->array(['data' => $company]);
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
            $company_name = $request->input('name');
            $post_no = $request->input('post_no');
            $order_no = $request->input('order_no');
            $result = OrderDeliver::deliver($order_no, $company_name, $post_no);

            return $this->response->created()->setContent(['data' => $result]);
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $order_no)
    {
        try {
            $company_name = $request->input('name');
            $post_no = $request->input('post_no');
            $result = OrderDeliver::deliver($order_no, $company_name, $post_no);

            return $this->response->created()->setContent(['data' => $result]);
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($order_no)
    {
        try {
            $result = OrderDeliver::cancelDeliver($order_no);
            if ($result) {
                return $this->response->noContent();
            }

            return $this->response->accepted();
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
    }
}
