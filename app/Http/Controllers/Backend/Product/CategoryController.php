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
class CategoryController extends Controller
{
    public function index()
    {
        try {
            $records = $this->api->get('api/admin/categories/');
            $categories = $records['data'];
            return view('backend.categories.index', compact('categories'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function create()
    {
        try {
            $categories = $this->api->get('api/admin/categories/')['data'];
            return view('backend.categories.create', compact('categories'));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function store(Request $request)
    {
        try {

            $data = $request->all();

            $result = $this->api->post('api/admin/categories', [
                'name' => array_get($data, 'name', ''),
                'pid' => array_get($data, 'pid', null),
                'cover_image' => array_get($data, 'cover_image', ''),
                'desc' => array_get($data, 'desc', '')
            ]);

            if ($result) {
                return redirect('/admin/categories');
            }


        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    public function show($id)
    {

        $category = $this->api->get('api/admin/categories/' . $id)['data'];
        $categories = $this->api->get('api/admin/categories')['data'];
        return view('backend.categories.show', compact('categories', 'category'));
    }

    public function update($id, Request $request)
    {
        try {
            $data = $request->all();

            $result = $this->api->put('api/admin/categories/' . $id, [
                'name' => array_get($data, 'name', ''),
                'cover_image' => array_get($data, 'cover_image', ''),
                'desc' => array_get($data, 'desc', '')
            ]);

            if ($result) {
                return redirect('/admin/categories');
            }
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->api->delete('api/admin/categories/' . $id);

            return redirect('/admin/categories');

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }
}
