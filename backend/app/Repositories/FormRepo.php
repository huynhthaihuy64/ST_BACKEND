<?php

namespace App\Repositories;

use App\Models\Form;

class FormRepo extends EloquentRepo
{

    public function getModel()
    {
        return Form::class;
    }

    /**
     * Find form by id
     * 
     * @param int $id
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getById(int $id)
    {
        return $this->model->where('id', $id)->with('field_types_forms')->get();
    }

    /**
     * Show data form
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return $this->model->where('link', '<>', 'null')->with('field_types_forms')->get();
    }

    /**
     * Update form by id
     * 
     * @param array $params
     * @param int $id
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function updateForm(array $params, int $id)
    {
        return $this->model->where('id', '=', $id)->update($params);
    }

    /**
     * Create form
     * 
     * @param array $params
     * 
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function insertForm(array $params)
    {
        return $this->model->create($params);
    }
}
