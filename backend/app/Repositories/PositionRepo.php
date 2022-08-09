<?php

 namespace App\Repositories;

 use App\Models\Position;

 class PositionRepo extends EloquentRepo
 {

    public function getModel()
    {
        return Position::class;
    }

    /**
     * Show position
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll()
    {
        return $this->model->select('id', 'name')->get();
    }
 }
