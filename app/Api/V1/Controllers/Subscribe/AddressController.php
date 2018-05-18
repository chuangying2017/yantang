<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Requests\Request;
use App\Api\V1\Requests\Subscribe\AddressRequest;
use App\Api\V1\Transformers\Mall\AddressTransformer;
use App\Repositories\Address\AddressRepositoryContract;
use App\Repositories\Station\District\DistrictRepositoryContract;
use App\Services\Preorder\PreorderAssignServiceContact;


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
            'street' => $request->input('street'),
            'zip' => '',
            'district_id' => $district_id,
            'longitude' => $request->input('longitude'),//经度
            'latitude' => $request->input('latitude'),//维度
            'default_status'=> $request->input('default_status') ?: 0
        ];
        $station = $assignService->assign($data['longitude'], $data['latitude'], $district['id']);//查看地址的经纬度距离哪个站点比较近
        //这里返回的是距离最近的站点id 如果没有就是空
        if (!$station) {
            $this->response->errorNotFound('该地址暂时未支持配送002');
        }

        $data['station_id'] = $station['id'];

        $address = $this->addressRepo->addAddress($data);

        return $this->response->item($address, new AddressTransformer())->setStatusCode(201);
    }


    public function update($address_id, AddressRequest $request, PreorderAssignServiceContact $assignService, DistrictRepositoryContract $districtRepo)
    {
        $district = $districtRepo->get($request->input('district_id'));//区域Id

        $data = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'province' => '广东省',
            'city' => '广州市',
            'district' => $district['name'],
            'street' => $request->input('street'),
            'zip' => '',
            'longitude' => $request->input('longitude'),
            'latitude' => $request->input('latitude'),
        ];

        $station = $assignService->assign($data['longitude'], $data['latitude'], $district['id']);

        if (!$station) {
            $this->response->errorNotFound('该地址暂时未支持配送001');
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

    public function show($address_id){

        $address_data = $this->addressRepo->getAddress($address_id);

        return $this->response->item($address_data, new AddressTransformer)->setStatusCode('201');
    }


    public function edit($address_id, $default_status, $being_id=null){//{address_id}/edit/{default_status}/{being_id}

        if ($being_id === null){ //如果所有地址都没有默认地址的话，当设置默认地址的时候后面的being_id不用传过来
            $this->addressRepo->updateDefault($address_id, ['default_status'=>'1']);
            $status = ['status'=>1,'msg'=>'操作成功'];
        }else{

            $callback_value = $this->addressRepo->updateDefault($address_id, ['default_status'=> '0']);
            switch ($callback_value){
                case 1:
                    $this->addressRepo->updateDefault($being_id, ['default_status'=>$default_status]);
                    $status = ['status'=>1,'msg'=>'操作成功'];
                    break;
                default:
                    $status = ['status'=>2,'msg'=>'操作失败001'];
                    break;
            }
        }


        return $this->response->array($status);
    }

    /**
     * @param $address_id
     * @param AddressRequest $request
     * @param PreorderAssignServiceContact $assignService
     * @param DistrictRepositoryContract $districtRepo
     * @return \Dingo\Api\Http\Response|void
     */
    public function updateAddress($address_id, AddressRequest $request, PreorderAssignServiceContact $assignService, DistrictRepositoryContract $districtRepo){
        $district = $districtRepo->get($request->input('district_id'));//区域Id

        $data = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'province' => '广东省',
            'city' => '广州市',
            'district' => $district['name'],
            'street' => $request->input('street'),
            'zip' => '',
            'longitude' => $request->input('longitude'),
            'latitude' => $request->input('latitude'),
            'district_id' => $district['id'],
        ];

        $request->input('detail') == '' ?: $data['detail'] = $request->input('detail');

        $station = $assignService->assign($data['longitude'], $data['latitude'], $district['id']);

        if (!$station) {
            $this->response->errorNotFound('该地址暂时未支持配送003');
        }

        $data['station_id'] = $station['id'];

        $address = $this->addressRepo->updateAddress($address_id, $data);

        if(!$address){
            $this->response->errorNotFound('更新地址失败');
        }

      return  $this->response->item($address, new AddressTransformer())->setStatusCode(201);
    }

    /*
     * 重新定义删除
     * */                           //删除的id     //要设置默认的id
    public function deleteFunction($address_id, $existing_id=null){

            $this->addressRepo->deleteAddress($address_id);

            if($existing_id){
                $this->addressRepo->updateDefault($existing_id, ['default_status'=>1]);
            }

            return $this->response->array(['status'=>1,'msg'=>'successfully']);
    }
}