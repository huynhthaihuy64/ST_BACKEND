<?php

namespace App\Repositories;

abstract class EloquentRepo
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder | \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * EloquentRepo constructor.
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * get model
     * @return string
     */
    abstract public function getModel();

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function setModel()
    {
        $this->model = app()->make($this->getModel());
    }

    /**
     * Get All
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get one
     *
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Insert multi records
     *
     * @param array $values
     * @return bool
     */
    public function insert(array $values)
    {
        return $this->model->insert($values);
    }

    /**
     * Update
     *
     * @param string $id
     * @param array $attributes
     * @return bool|mixed
     */
    public function update($id, array $attributes)
    {
        $result = $this->find($id);

        if ($result) {
            $result->update($attributes);
            return $result;
        }

        return false;
    }

    /**
     * Delete
     *
     * @param string $id
     * @return bool
     */
    public function delete($id)
    {
        $result = $this->find($id);

        if ($result) {
            try {
                $result->delete();
                return true;
            } catch (\Exception $e) {
                report($e);
                return false;
            }
        }

        return false;
    }

    /**
     * Getter for each filter
     *
     * @param string $field
     * @param string $value
     * @param string $operator
     * @return array
     */
    public function getFilter($field, $value, $operator = '=')
    {
        return compact('field', 'operator', 'value');
    }

    /**
     * Handle dynamic method calls into the repository.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->model->newQuery()->$method(...$parameters);
    }
}
