<?php
namespace App\Repositories\Integral\Address;

use App\Models\Integral\IntegralUserAddress;
use App\Repositories\Address\AddressRepositoryContract;
use App\Repositories\Integral\Supervisor\Supervisor;
use Mockery\Exception;

class UserAddress implements Supervisor
{

    protected $shopping_address;

    public function __construct(AddressRepositoryContract $addressRepositoryContract)
    {
        $this->shopping_address = $addressRepositoryContract;
    }

    public function get_all()
    {
        // TODO: Implement get_all() method.
    }

    public function find($where)
    {
        $model = new IntegralUserAddress();

            if (is_numeric($where)){
            $result =  $model->find($where);
            }
            elseif(is_array($where)){
            $result = $model->where($where)->first();
            }else
            {
                throw new Exception('parameter have is error',500);
            }

            return $result;
    }

    public function create(array $array)
    {
       return IntegralUserAddress::create(array_merge($this->array_address($array), ['user_id' => access()->id()]));
    }

    public function update($id, array $array)
    {
        // TODO: Implement update() method.
    }

    public function edit($id, $content)
    {
        // TODO: Implement edit() method.
    }

    public function delete($where)
    {
        // TODO: Implement delete() method.
    }

    public function user_integral_address()
    {
      //  $all_address = $this->shopping_address->getAllAddress();

    }

    public function array_address($data)
    {
        return array_only($data, [
            'name',
            'tel',
            'phone',
            'province',
            'city',
            'district',
            'detail',
            'street',
            'precise',
            'type'
        ]);
    }
}