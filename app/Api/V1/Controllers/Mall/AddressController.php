<?php namespace App\Api\V1\Controllers\Mall;

use App\Api\V1\Requests\Mall\AddressRequest;
use App\Api\V1\Transformers\Mall\AddressTransformer;
use App\Repositories\Address\AddressRepositoryContract;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addresses = $this->addressRepo->getAllAddress();

        return $this->response->collection($addresses, new AddressTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddressRequest $request)
    {
        $address = $this->addressRepo->addAddress($request->all());

        return $this->response->created()->setContent(['data' => $address]);
    }


    public function primary()
    {
        $address = $this->addressRepo->getPrimaryAddress();
        return $this->response->item($address, new AddressTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $address = $this->addressRepo->getAddress($id);
        return $this->response->item($address, new AddressTransformer());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddressRequest $request, $id)
    {
        $address = $this->addressRepo->updateAddress($id, $request->all());
        return $this->response->item($address, new AddressTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->addressRepo->deleteAddress($id);
        return $this->response->noContent();
    }
}
