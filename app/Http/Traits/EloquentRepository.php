<?php namespace App\Http\Traits;


trait EloquentRepository
{

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


}
