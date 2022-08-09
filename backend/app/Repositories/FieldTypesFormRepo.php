<?php

namespace App\Repositories;

use App\Models\FieldTypesForm;

class FieldTypesFormRepo extends EloquentRepo
{

    public function getModel()
    {
        return FieldTypesForm::class;
    }

    /**
     * Update filedtypeform
     * 
     * @param array $params
     * @param int $id
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function updateField(array $params, int $id)
    {
        return $this->model->where('id', '=', $id)->update($params);
    }

    /**
     * Create field
     * 
     * @param array $params
     * 
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function insertField(array $params)
    {
        return $this->model->create($params);
    }
}
