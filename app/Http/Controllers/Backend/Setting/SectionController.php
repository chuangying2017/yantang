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
class SectionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $sectionData = $request->all();
            $products = $sectionData['products'];
            unset($sectionData['products']);
            $section = $this->api->post('/api/admin/sections', $sectionData)['data'];
            $this->api->put('/api/admin/sections/' . $section['id'] . '/products', [
                'products' => $products
            ]);

            return [
                'id' => $section['id']
            ];

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $sectionData = $request->all();
            $products = $sectionData['products'];
            unset($sectionData['products']);
            $this->api->put('/api/admin/sections/' . $id, $sectionData);
            $this->api->put('/api/admin/sections/' . $id . '/products', [
                'products' => $products
            ]);
            return [
                'id' => $id
            ];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            $this->api->delete('api/admin/sections/' . $id);

            return 1;

        } catch (Exception $e) {

            return $e->getMessage();
        }
    }
}
