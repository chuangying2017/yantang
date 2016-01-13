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
            $data = $request->all();
            $data = array_except($data, '_token');
            return $this->api->raw()->post('/api/admin/nav', $data);
        
    }

    public function update($id, Request $request)
    {
       
            $data = $request->all();
            return $this->api->raw()->put('/api/admin/nav/' . $id, $data);
        
    }

    public function destroy($id)
    {
            return $this->api->raw()->delete('api/admin/nav/' . $id);
            return 1;
       
    }
}
