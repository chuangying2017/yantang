<?php
namespace App\Repositories\Integral\Card;

use App\Models\Integral\IntegralCard;
use App\Repositories\Integral\Supervisor\Supervisor;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class OperationMode implements Supervisor
{
    protected $card;

    public function __construct(CardVerify $cardVerify)
    {
        $this->card = $cardVerify;
    }

    public function get_all($paginate = CardProtocol::CARD_PAGINATE_NUM)
    {
       return IntegralCard::query()->paginate($paginate);
    }

    public function find($where)
    {
        return IntegralCard::query()->find($where);
    }

    public function create(array $array)
    {
        $card_model = new IntegralCard();

        $card_model->fill($this->array_verify($this->array_card($array)));

        return $card_model->save();
    }

    public function update($id, array $array)
    {
       $card_model = IntegralCard::find($id);

       $card_model->fill($this->array_card($array));

       return $card_model->save();
    }

    public function edit($id, $content)
    {
        // TODO: Implement edit() method.
    }

    public function delete($where)
    {
        return IntegralCard::destroy($where);
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
                if (!array_key_exists($value,$data))
                {
                    $data[$value] = isset(CardProtocol::$card_array_data[$value])
                        ? CardProtocol::$card_array_data[$value]
                        : $data['issue_num'];
                }
            }

            return $data;
    }

    public function member_draw($id)
    {
        try {
            $result =
            $this->
            card->
            set_model($this->find($id))->
            set_verifyData(['user_id' => access()->id(), 'name' => '积分卡领取'])->limitsOrLoose();
            if (!empty($result->get_errorMessage()))
            {
                return $result->get_errorMessage();
            }
            return $result->dataCommit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            exit($e->getMessage());
        }
    }
}