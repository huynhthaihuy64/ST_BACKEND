<?php


namespace App\Repositories;


use App\Models\User;

class UserRepo extends EloquentRepo
{
    /**
     * @inheritDoc
     */
    public function getModel()
    {
        return User::class;
    }

    /**
     * @param $offset
     * @param $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll($offset, $limit)
    {
        return $this->model->offset($offset)->limit($limit)->get();
    }
}
