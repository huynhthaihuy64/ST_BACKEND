<?php

namespace App\Repositories;

use App\Models\EmployeesTechnology;

class EmployeeTechnologyRepo extends EloquentRepo
{
    public function getModel()
    {
        return EmployeesTechnology::class;
    }

    /**
     * Create employee technology
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function insertTechnology(array $params)
    {
        return $this->model->create($params);
    }

    /**
     * Create employee technology
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function deleteTechnology(int $id)
    {
        return $this->model->where('employee_id', $id)->delete();
    }

}
