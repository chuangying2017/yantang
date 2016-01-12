<?php
namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 10/12/2015
 * Time: 5:57 PM
 */
class NavController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data = array_except($data, '_token');
            return $this->api->post('/api/admin/nav', $data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $data = $request->all();
            return $this->api->put('/api/admin/nav/' . $id, $data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            return $this->api->delete('api/admin/nav/' . $id);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
