<?php
namespace App\Repositories\Integral\Card;

use App\Models\Integral\IntegralCard;
use App\Repositories\Integral\Supervisor\Supervisor;

class OperationMode implements Supervisor
{

    public function get_all()
    {
        // TODO: Implement get_all() method.
    }

    public function find($where)
    {
        // TODO: Implement find() method.
    }

    public function create(array $array)
    {
        $card_model = new IntegralCard();

        $card_model->fill($this->array_verify($this->array_card($array)));

        return $card_model->save();
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

    public function array_card($array)
    {
        return array_only($array, [
            'name','give','status','type','mode','cover_image','issue_num','draw_num',
            'start_time','end_time','remain','get_member'
        ]);
    }

    protected $filter = ['status','type','mode','remain'];

    public function array_verify($data)
    {
            foreach ($this->filter as $key=>$value)
            {
                if (!in_array($value,$data))
                {
                    $data[$value] = isset(CardProtocol::$card_array_data[$value])
                        ? CardProtocol::$card_array_data[$value]
                        : $data['issue_num'];
                }
            }

            return $data;
    }
}