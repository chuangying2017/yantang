<?php
namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 10/12/2015
 * Time: 5:57 PM
 */
class GroupController extends Controller
{
    /**
     * @return $this|string
     */
    public function index()
    {
        try {
            $records = $groups = $this->api->get('api/admin/groups');
            return view('backend.groups.index')->with('groups', $records);
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
        return view('backend.groups.create');
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

            $group = $this->api->get('api/admin/groups/' . $id);
            return view('backend.groups.show')->with('group', $group);
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
