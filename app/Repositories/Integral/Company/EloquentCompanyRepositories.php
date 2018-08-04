<?php
namespace App\Repositories\Integral\Company;

use App\Models\Company\CompanyModel;
use App\Repositories\Integral\Supervisor\Supervisor;

class EloquentCompanyRepositories implements Supervisor
{

    public function get_all()
    {
       return CompanyModel::status(CompanyProtocol::COMPANY_STATUS_ACTIVE)
           ->where('type','=',CompanyProtocol::COMPANY_TYPE_EXPRESS)
           ->get();
    }

    public function find($where)
    {
        $company = CompanyModel::query();
        if (is_numeric($where))
        {
            return $company->find($where);
        }
            return $company->where($where)->first();
    }

    public function create(array $array)
    {
        $company = new CompanyModel();

        $company->fill(array_merge($this->array_company($array),
                ['status' => CompanyProtocol::COMPANY_STATUS_ACTIVE,
                'type'=> CompanyProtocol::COMPANY_TYPE_EXPRESS]));

        return $company->save();
    }

    public function update($id, array $array)
    {
        $company = $this->find($id);

        $company->fill($this->array_company($array));

        return $company->save();
    }

    public function edit($id, $content)
    {
        // TODO: Implement edit() method.
    }

    public function delete($where)
    {
        return CompanyModel::destroy($where);
    }

    public function array_company($data)
    {
        return array_only($data,[
            'name',
            'detail',
            'status',
            'type',
        ]);
    }
}