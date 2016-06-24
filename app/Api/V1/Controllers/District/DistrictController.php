<?php namespace App\Api\V1\Controllers\District;

use App\API\V1\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    const PERPAGE = 20;

    public function __construct()
    {

    }

    public function index()
    {
        $district = District::all();
        return $this->response->array(['data' => $district]);
    }

    public function store(Request $request)
    {
        $input = $request->only(['name']);
        $district = District::create($input);
        return $this->response->array(['data' => $district]);
    }

    public function show($id)
    {
        $district = District::find($id);
        return $this->response->array(['data' => $district]);
    }

    public function update(Request $request, $id)
    {
        $input = $request->only(['name']);
        $district = District::find($id)->fill($input);
        $district->save();
        return $this->response->array(['data' => $district]);
    }

    public function destroy($id)
    {
        $district = District::find($id)->delete();
        $data = "删除失败";
        if ($district) {
            $data = "删除成功";
        }
        return $this->response->array(['data' => $data]);
    }
}
