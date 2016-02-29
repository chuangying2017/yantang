<?php
namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\BackendController;
use Exception;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 10/12/2015
 * Time: 5:57 PM
 */
class AttributeController extends BackendController
{
    public function index()
    {
        $attributes = $this->api->get('api/admin/attributes');
        if (isset($attributes['data'])) {
            $attributes = $attributes['data'];
        }
        return view('backend.attributes.index', compact('attributes'));
    }

    public function create()
    {
        try {
            return view('backend.attributes.create');
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    public function store(Request $request)
    {
        try {
            $name = $request->get('name');
            $this->api->post('api/admin/attributes', [
                "name" => $name
            ]);
            return redirect('admin/attributes');
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    public function destory($id)
    {
        try {
            $this->api->delete('admin/attributes/' . $id);
            return redirect('admin/attributes');
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }
}
