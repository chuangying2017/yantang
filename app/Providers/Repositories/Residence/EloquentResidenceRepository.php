<?php namespace App\Repositories\Residence;

use App\Models\Residence;
use App\Repositories\Backend\AccessProtocol;
use DB;

class EloquentResidenceRepository implements ResidenceRepositoryContract {
    const PER_PAGE = 15;
    public function createResidence($residence_data)
    {
        return Residence::create([
            'name' => $residence_data['name'],
            'aliases' => $residence_data['aliases'],
            'goal' => $residence_data['goal'],
            'district_id' => $residence_data['district_id'],
        ]);
    }

    public function updateResidence($residence_id, $residence_data)
    {
        $residence_data['aliases'] = array_filter(array_unique(explode(',',$residence_data['aliases'])));
        if(!$residence_data['aliases']){
            return false;
        }
        $residence_data['aliases'] = implode(',',$residence_data['aliases']);
        $residence = $this->getResidence($residence_id);
        $residence->fill(array_only($residence_data, [
            'name',
            'aliases',
            'goal',
            'district_id',
        ]));
        $residence->save();

        return $residence;
    }

    public function deleteResidence($station_id)
    {
        return Residence::destroy($station_id);
    }

    public function getResidence($station_id)
    {
        return Residence::query()->find($station_id);
    }

    public function getAll($only_id = false)
    {
        if ($only_id) {
            return Residence::query()->pluck('id')->all();
        }
        return Residence::query()->get();
    }

    public function getAllPaginated($onyl_deleted = false, $per_page = self::PER_PAGE)
    {
        $query = Residence::query();
        if ($onyl_deleted) {
            $query->whereNotNull('deleted_at');
        }
        return $query->paginate($per_page);
    }
}
