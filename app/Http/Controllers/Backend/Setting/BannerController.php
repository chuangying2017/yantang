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
class BannerController extends Controller
{
    public function index()
    {
        $sliders = $this->api->get('api/admin/banners?type=slider');
        $grids = $this->api->get('api/admin/banners?type=grid');
        javascript()->put([
            'config' => [
                'api_url' => url('api/'),
                'base_url' => url('/')
            ],
            'token' => csrf_token(),
            'sliders' => $sliders['data'],
            'grids' => $grids['data']
        ]);

        return view('backend.setting.banner');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            return $this->api->post('/api/admin/banners', $data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $data = $request->all();
            $this->api->put('/api/admin/banners/' . $id, $data);
            return 1;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            $this->api->delete('api/admin/banners/' . $id);

            return 1;

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }
}
