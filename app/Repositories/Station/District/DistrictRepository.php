<?php namespace App\Repositories\Station\District;

use App\Models\District;

class DistrictRepository implements DistrictRepositoryContract {

    public function getAll()
    {
        return District::query()->get();
    }

    public function create($name)
    {
        return District::query()->updateOrCreate([
            'name' => $name
        ]);
    }

    public function update($id, $name)
    {
        $district = $this->get($id);
        $district->name = $name;
        $district->save();

        return $district;
    }

    public function delete($id)
    {
        return District::destroy($id);
    }

    public function get($id)
    {
        return District::query()->findOrFail($id);
    }

    public function increase($id, $count)
    {
        $district = $this->get($id);
        $district->station_count += $count;
        $district->save();

        return $district;
    }

    public function decrease($id, $count)
    {
        $district = $this->get($id);
        $district->station_count = $district->station_count > $count ? $district->station_count - $count : 0;
        $district->save();

        return $district;
    }
}
