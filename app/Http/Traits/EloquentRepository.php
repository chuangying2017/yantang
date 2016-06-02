<?php namespace App\Http\Traits;


trait EloquentRepository
{

    /**
     * @param $per_page 0不分页
     * @param array $where where条件数组 [['field'=>'part_id_card', 'value'=>$input['id_card'], 'compare_type'=>'=']]
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function Paginated($per_page, $where = [], $order_by = 'id', $sort = 'asc')
    {
        $query = app($this->moder());

        $query = $this->where($where, $query);

        if (!empty($order_by) && !empty($sort)) {
            $query = $query->orderBy($order_by, $sort);
        }
        if (!empty($per_page)) {
            $query = $query->paginate($per_page);
        } else {
            $query = $query->get();
        }

        return $query;
    }

    public function getInfoById($id)
    {
        $query = app($this->moder());
        return $query->find($id);
    }

    public function where($where = [], $query)
    {
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                if (!empty($value['field']) && !empty($value['compare_type']) && !empty($value['value'])) {
                    $query = $query->where($value['field'], $value['compare_type'], $value['value']);
                }
            }
        }
        return $query;
    }

    public function create($input)
    {
        $query = app($this->moder());
        return $query->create($input);
    }

    public function update($input, $id)
    {
        $query = app($this->moder());
        $query = $query->find($id)->fill($input);
        $query->save();
        return $query;
    }
}
