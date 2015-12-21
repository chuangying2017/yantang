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
class ProductController extends Controller {

    /**
     * @return \Exception|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        try {
            $products = $this->api->get('api/admin/products/');

            return view('backend.product.index', compact('products'));
        } catch (\Exception $e) {
            return $e;
        }

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->setJs();

        return view('backend.product.create');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $product = $this->api->raw()->get('api/admin/products/' . $id);
        $this->setJs(json_decode($product->content(), true));

        return view('backend.product.create');
    }

    /**
     * @param Request $request
     * @return int|string
     */
    public function store(Request $request)
    {

        try {
            $data = $request->all();

            $result = $this->api->post('api/admin/products', $data);

            if ($result) {
                return 1;
            }
        } catch (Exception $e) {

            return $e->getMessage();
        }

    }

    /**
     * @param $id
     * @param Request $request
     * @return int|string
     */
    public function update($id, Request $request)
    {

        try {
            $data = $request->all();

            $result = $this->api->put('api/admin/products/' . $id, $data);

            if ($result) {
                return 1;
            }
        } catch (Exception $e) {

            return $e->getMessage();
        }

    }

    /**
     * @param null $product
     * @throws \Exception
     */
    private function setJs($product = null)
    {
        $categories = $this->api->get('api/admin/categories')['data'];
        $groups = $this->api->get('api/admin/groups');
        $attributes = $this->api->get('api/admin/attributes');
        $brands = $this->api->get('api/admin/brands');
        $qiniu_token = $this->api->get('api/admin/images/token')['data'];
        $data = [
            'config'      => [
                'api_url'  => url('api/'),
                'base_url' => url('/')
            ],
            'categories'  => $categories,
            'groups'      => $groups,
            'brands'      => $brands,
            'token'       => csrf_token(),
            'qiniu_token' => $qiniu_token,
            'attributes'  => $attributes
        ];

        if ($product) {
            $data['product'] = $product;
        }
        javascript()->put($data);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function destroy($id)
    {
        try {
            $this->api->delete('api/admin/products/' . $id);

            return redirect('admin/products');
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @param $action
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function operate($id, $action)
    {
        try {
            $this->api->put('api/admin/products/operate', [
                "action" => $action,
                "products_id" => [$id]
            ]);
            return redirect('admin/products');
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }
}
