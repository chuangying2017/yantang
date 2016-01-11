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
class BrandController extends Controller
{
    //todo@bryant: error handler
    public function index()
    {
        try {
            $brands = $this->api->get('api/admin/brands');
            return view('backend.brands.index', compact('brands'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function create()
    {
        try {
            $brand = (object)["cover_image" => ''];
            return view('backend.brands.create', compact('brand'));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function store(Request $request)
    {
        try {

            $data = $request->all();

            $result = $this->api->post('api/admin/brands', [
                'name' => array_get($data, 'name', ''),
                'cover_image' => array_get($data, 'cover_image', '')
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
                'name' => array_get($data, 'name', ''),
                'cover_image' => array_get($data, 'cover_image', '')
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
