<?php

namespace App\Repositories;

class CommonRepo
{

    /**
     * @param Model $model,
     * @param int $limit
     * @param string $fieldSort,
     * @param asc|desc $sortDirection
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function queryTable($model, int $limit, string $fieldSort, string $sortDirection)
    {
        return $model->orderBy($fieldSort, $sortDirection)->paginate($limit);
    }

    /**
     * @param Model $model,
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function groupByStatus($model)
    {
        return $model->select('status', $model::raw('count(*) as total'))
            ->groupBy('status')->get();
    }
    /**
     * Update the specified resource in storage.
     * 
     * @param Model $model
     * @param array $params
     * @param int $id
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function updateTable($model, array $params, int $id)
    {
        return $model->where('id', $id)->update($params);
    }
}
