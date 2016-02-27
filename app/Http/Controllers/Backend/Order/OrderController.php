<?php

namespace App\Http\Controllers\Backend\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 10/12/2015
 * Time: 5:57 PM
 */
class OrderController extends Controller
{
    /**
     * @return $this|string
     */
    public function index(Request $request)
    {
        //todo@bryant: wait for api
        $page = $request->get('page') ?: '';
        $status = $request->get('status') ?: '';
        $keyword = $request->get('keyword') ?: '';
        try {
            $records = $this->api->get('api/admin/orders?page=' . $page . '&status=' . $status . '&keyword=' . $keyword);
            javascript()->put([
                'config' => [
                    'api_url' => url('api/'),
                    'base_url' => url('/')
                ],
                'token' => csrf_token(),
                'pagination' => $records->toArray()
            ]);
            return view('backend.orders.index')->with('records', $records);
        } catch (Exception $e) {

            return $e->getMessage();
        }

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
//    	return view('backend.orders.detail');
        return view('backend.merchant.create');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $result = $this->api->post('api/admin/groups', [
                'name' => array_get($data, 'name', ''),
                'group_cover' => array_get($data, 'group_cover', ''),
                'desc' => array_get($data, 'desc', '')
            ]);

            if ($result) {
                return redirect('/admin/groups');
            }

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function show($id)
    {
        try {
            $order = $this->api->get('api/admin/orders/' . $id);
            $expressCompanies = $this->api->get('api/admin/deliver/company');
            javascript()->put([
                'config' => [
                    'api_url' => url('api/'),
                    'base_url' => url('/')
                ],
                'order' => $order
            ]);
            return view('backend.orders.detail', [
                'order' => $order,
                'expressCompanies' => $expressCompanies
            ]);
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return string
     */
    public function update($id, Request $request)
    {
        try {
            $data = $request->all();

            $this->api->put('api/admin/groups/' . $id, [
                'name' => array_get($data, 'name', ''),
                'group_cover' => array_get($data, 'group_cover', ''),
                'desc' => array_get($data, 'desc', '')
            ]);
            return redirect('/admin/groups');
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function destroy($id)
    {
        try {
            $this->api->delete('api/admin/groups/' . $id);
            return redirect('/admin/groups');
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }
}
