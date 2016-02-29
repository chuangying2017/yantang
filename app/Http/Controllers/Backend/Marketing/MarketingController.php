<?php
namespace App\Http\Controllers\Backend\Marketing;

use App\Http\Controllers\BackendController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 10/12/2015
 * Time: 5:57 PM
 */
class MarketingController extends BackendController
{
    //todo@bryant: error handler
    public function index()
    {
        try {
            $orders = $this->api->get('api/admin/orders');
            return view('backend.orders.index', compact('orders'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function create()
    {
        try {
            return view('backend.brands.create');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function store(Request $request)
    {
        try {

            $data = $request->all();

            $result = $this->api->post('api/admin/brands', [
                'name' => array_get($data, 'name', '')
            ]);

            if ($result) {
                return redirect('/admin/brands');
            }


        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    public function show($id)
    {

        $brand = $this->api->get('api/admin/brands/' . $id);
        return view('backend.brands.show', compact('brand'));
    }

    public function update($id, Request $request)
    {
        try {
            $data = $request->all();

            $result = $this->api->put('api/admin/brands/' . $id, [
                'name' => array_get($data, 'name', '')
            ]);

            if ($result) {
                return redirect('/admin/brands');
            }
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->api->delete('api/admin/brands/' . $id);

            return redirect('/admin/brands');

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }
}
