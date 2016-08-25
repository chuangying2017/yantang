<?php namespace App\Api\V1\Controllers\Admin\Subscribe;

use App\Api\V1\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscribe\PreRecharge;

class RechargeAmountController extends Controller
{

    public function __construct()
    {
    }

    public function index()
    {
        $PreRecharge = PreRecharge::all();
        return $this->response->array(['data' => $PreRecharge]);
    }

    public function store(Request $request)
    {
        $input = $request->only(['amount']);
        $PreRecharge = PreRecharge::create($input);
        return $this->response->array(['data' => $PreRecharge]);
    }

    public function show($id)
    {
        $PreRecharge = PreRecharge::find($id);
        return $this->response->array(['data' => $PreRecharge]);
    }

    public function update(Request $request, $id)
    {
        $input = $request->only(['amount']);
        $PreRecharge = PreRecharge::find($id)->fill($input);
        $PreRecharge->save();
        return $this->response->array(['data' => $PreRecharge]);
    }

    public function destroy($id)
    {
        $PreRecharge = PreRecharge::find($id)->delete();
        $data = "删除失败";
        if ($PreRecharge) {
            $data = "删除成功";
        }
        return $this->response->array(['data' => $data]);
    }
}
