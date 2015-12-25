<?php
namespace App\Http\Controllers\Backend\Image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 10/12/2015
 * Time: 5:57 PM
 */
class ImageController extends Controller
{
    /**
     * @return $this|string
     */
    public function index(Request $request)
    {
        //todo@bryant: wait for api
        try {
            $page = $request->get('page');
            $records = $this->api->raw()->get('api/admin/images?page=' . $page);
            $images = json_decode($records->content(), true);
            return view('backend.media.images.index')->with('images', $images);
        } catch (Exception $e) {

            return $e->getMessage();
        }

    }


    /**
     * @param $id
     * @return string
     */
    public function destroy($id)
    {
        try {
            $data = [
                'images_id' => [$id]
            ];
            $this->api->delete('api/admin/images/', $data);
            return redirect('/admin/images');
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    public function upload()
    {
        try {
            $qiniu_token = $this->api->get('api/admin/images/token')['data'];
            javascript()->put([
                "qiniu_token" => $qiniu_token
            ]);
            return view('backend.media.images.upload');
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }
}
