<?php


namespace App\Repositories;


use App\Models\Post;

class PostRepo extends EloquentRepo
{
    /**
     * @inheritDoc
     */
    public function getModel()
    {
        return Post::class;
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
