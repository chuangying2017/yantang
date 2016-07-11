<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Requests\Subscribe\AddressRequest;
use App\Api\V1\Transformers\Mall\AddressTransformer;
use App\Repositories\Address\AddressRepositoryContract;
use App\Repositories\Station\District\DistrictRepositoryContract;
use App\Services\Preorder\PreorderAssignServiceContact;
use Illuminate\Http\Request;

use App\Http\Requests;

class AddressController extends Controller {

    /**
     * @var AddressRepositoryContract
     */
    private $addressRepo;

    /**
     * AddressController constructor.
     * @param AddressRepositoryContract $addressRepo
     */
    public function __construct(AddressRepositoryContract $addressRepo)
    {
        $this->addressRepo = $addressRepo;
    }

    public function index()
    {
        $address = $this->addressRepo->getAllSubscribeAddress();
        return $this->response->collection($address, new AddressTransformer());
    }

    public function store(AddressRequest $request, PreorderAssignServiceContact $assignService, DistrictRepositoryContract $districtRepo)
    {
        $district_id = $request->input('district_id');
        $district = $districtRepo->get($district_id);

        $data = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'province' => '广东省',
            'city' => '广州市',
            'district' => $district['name'],
            'detail' => $request->input('detail'),
            'zip' => '',
            'district_id' => $district_id,
            'longitude' => $request->input('longitude'),
            'latitude' => $request->input('latitude'),
        ];

        $station = $assignService->assign($data['longitude'], $data['latitude'], $district['id']);

        if (!$station) {
            $this->response->errorNotFound('该地址暂时未支持配送');
        }

        $data['station_id'] = $station['id'];

        $address = $this->addressRepo->addAddress($data);

        return $this->response->item($address, new AddressTransformer())->setStatusCode(201);
    }


    public function update($address_id, AddressRequest $request, PreorderAssignServiceContact $assignService, DistrictRepositoryContract $districtRepo)
    {
        $district = $districtRepo->get($request->input('district_id'));

        $data = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'province' => '广东省',
            'city' => '广州市',
            'district' => $district['name'],
            'detail' => $request->input('detail'),
            'zip' => '',
            'longitude' => $request->input('longitude'),
            'latitude' => $request->input('latitude'),
        ];

        $station = $assignService->assign($data['longitude'], $data['latitude'], $district['id']);

        if (!$station) {
            $this->response->errorNotFound('该地址暂时未支持配送');
        }

        $data['station_id'] = $station['id'];

        $address = $this->addressRepo->updateAddress($address_id, $data);

        return $this->response->item($address, new AddressTransformer())->setStatusCode(201);
    }

    public function destroy($address_id)
    {
        $this->addressRepo->deleteAddress($address_id);

        return $this->response->noContent();
    }

}
