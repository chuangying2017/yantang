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
class FrontpageController extends Controller
{
    public function index()
    {
        $navs = $this->api->get('api/admin/nav');
        $sliders = $this->api->get('api/admin/banners?type=slider');
        $grids = $this->api->get('api/admin/banners?type=grid');
        $sections = $this->api->get('api/admin/sections');
        javascript()->put([
            'token' => csrf_token(),
            'navs' => $navs['data'],
            'sliders' => $sliders['data'],
            'grids' => $grids['data'],
            'sections' => $sections['data']
        ]);

        return view('backend.setting.frontpage');
    }
}
