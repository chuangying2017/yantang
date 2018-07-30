<?php
namespace App\Repositories\Integral\Company;

use App\Models\Company\CompanyModel;
use App\Repositories\Integral\Supervisor\Supervisor;

class EloquentCompanyRepositories implements Supervisor
{

    public function get_all()
    {
        return 'string';
    }

    public function find($where)
    {
        // TODO: Implement find() method.
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

    public function array_company($data)
    {
        return array_only($data,[
            'name',
            'detail',
        ]);
    }
}