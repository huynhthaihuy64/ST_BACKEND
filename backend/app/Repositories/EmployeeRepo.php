<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Repositories\CommonRepo;
use App\Enums\EmployeeStatusType;

class EmployeeRepo extends EloquentRepo
{
    public function getModel()
    {
        return Employee::class;
    }

    /**
     * @var CommonRepo
     */
    private $commonRepo;

    /**
     * @param CommonRepo $commonRepo
     */
    public function __construct(CommonRepo $commonRepo)
    {
        $this->commonRepo = $commonRepo;
        parent::__construct();
    }

    /**
     * Create employee
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function insertEmployee(array $params)
    {
        $params['status'] = EmployeeStatusType::ACTIVE;
        return $this->model->create($params);
    }

    /**
     * Get one employee
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getById(int $id)
    {
        return $this->model->with(['positions', 'technologies'])->where('id', $id)->first();
    }

    /**
     * Show employee
     *
     * @param int $limit
     * @param array $params
     * @param string $fieldSort
     * @param asc|desc $sortDirection
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll(int $limit, array $params, string $fieldSort, string $sortDirection)
    {
        $query = $this->model;
        $query = $query->filter($params)->with('positions');

        return $this->commonRepo->queryTable($query, $limit, $fieldSort, $sortDirection);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function groupByStatus()
    {
        return $this->commonRepo->groupByStatus($this->model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param array $params
     * @param string $technologyId
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function updateEmployee(int $id, array $params, string $technologyId)
    {
        $this->model->find($id)->technologies()->sync($technologyId);
        $query = $this->model->with(
            array(
                'positions' => function ($querySlect) {
                    $querySlect->select('id', 'name');
                },
                'technologies' => function ($querySlect) {
                    $querySlect->select('technology_id', 'name');
                },
            )
        );
        return $this->commonRepo->updateTable($query, $params, $id);
    }

    /**
     * Update status
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function setStatusById(int $id)
    {
        $status = ['status' => EmployeeStatusType::INACTIVE];
        return $this->model->where('id', $id)->update($status);
    }

    /**
     * Get total employee
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTotalEmployees(){
        return $this->model->get()->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function groupByPosition($year)
    {
        return $this->model->join('positions', 'employees.position_id', '=', 'positions.id')
            ->select($this->model::raw('positions.name, COUNT(*) AS total'))
            ->whereYear('start_date', '=', $year)
            ->groupBy('positions.name')->get();
    }
}
