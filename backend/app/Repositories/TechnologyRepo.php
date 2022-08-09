<?php

 namespace App\Repositories;

 use App\Models\Technology;

 class TechnologyRepo extends EloquentRepo
 {

    public function getModel()
    {
        return Technology::class;
    }

    /**
     * Get all technologies
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll()
    {
        return $this->model->select('id', 'name')->get();
    }
 }
