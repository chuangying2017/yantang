<?php
namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\BackendController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 10/12/2015
 * Time: 5:57 PM
 */
class CategoryController extends BackendController
{
    public function __construct()
    {
        $this->setJs();
    }

    //todo@bryant: error handler
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

            $result = $this->api->raw()->post('api/admin/categories', [
                'name' => array_get($data, 'name', ''),
                'pid' => array_get($data, 'pid', 0),
                'category_cover' => array_get($data, 'category_cover', ''),
                'desc' => array_get($data, 'desc', '')
            ]);

            if ($result->getStatusCode() == 201) {
                return redirect()->to('/admin/categories');
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
                'category_cover' => array_get($data, 'category_cover', ''),
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

    private function setJs()
    {

        $qiniu_token = $this->api->get('api/admin/images/token')['data'];
        $data = [
            'config' => [
                'api_url' => url('api/'),
                'base_url' => url('/'),
                'default_img' => 'http://7xpdx2.com2.z0.glb.qiniucdn.com/default.jpeg?imageView2/1/w/100'
            ],
            'token' => csrf_token(),
            'qiniu_token' => $qiniu_token
        ];
        javascript()->put($data);
    }
}
