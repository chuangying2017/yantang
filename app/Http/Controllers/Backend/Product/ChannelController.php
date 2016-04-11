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
class ChannelController extends BackendController
{
    //todo@bryant: error handler
    public function index()
    {
        try {
            $channels = $this->api->get('api/admin/channels');
            return view('backend.channels.index', compact('channels'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function create()
    {
        try {
            $brands = $this->api->get('api/admin/brands');
            return view('backend.channels.create', compact('brands'));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function store(Request $request)
    {
        try {

            $data = $request->all();
            $result = $this->api->raw()->post('api/admin/channels', [
                'name' => array_get($data, 'name', ''),
                'brand_ids' => array_get($data, 'brand_ids', [])
            ]);
            if ($result->getStatusCode() == 201) {
                return redirect()->to('/admin/channels');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function show($id)
    {

        $channel = $this->api->get('api/admin/channels/' . $id);
        $brand_ids = [];
        foreach ($channel->brands as $brand) {
            $brand_ids[] = $brand->id;
        }
        $brands = $this->api->get('api/admin/brands');
        return view('backend.channels.show', compact('channel', 'brand_ids', 'brands'));
    }

    public function update($id, Request $request)
    {
        try {
            $data = $request->all();

            $result = $this->api->put('api/admin/channels/' . $id, [
                'name' => array_get($data, 'name', ''),
                'brand_ids' => array_get($data, 'brand_ids', '')
            ]);

            if ($result) {
                return redirect('/admin/channels');
            }
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->api->delete('api/admin/channels/' . $id);

            return redirect('/admin/channels');

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }
}
