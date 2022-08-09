<?php

namespace App\Services;

use App\Repositories\EmployeeRepo;
use App\Services\Common\FileService;
use App\Repositories\EmployeeTechnologyRepo;

/**
 * Class EmployeeService
 * @package App\Services
 */
class EmployeeService
{
    /**
     * @var const DIRECTORY_PROFILE
     */
    public const DIRECTORY_PROFILE = 'Profile';

    /**
     * @var EmployeeRepo
     */
    private $employeeRepo;

    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @var EmployeeTechnologyRepo
     */
    private $employeeTechnologyRepo;

    /**
     * @param EmployeeRepo $employeeRepo
     * @param EmployeeTechnologyRepo $employeeTechnologyRepo
     */
    public function __construct(EmployeeRepo $employeeRepo, EmployeeTechnologyRepo $employeeTechnologyRepo)
    {
        $this->employeeRepo = $employeeRepo;
        $this->fileService = new FileService(config('storage.disk'));
        $this->employeeTechnologyRepo = $employeeTechnologyRepo;
    }

    /**
     * Create employee
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function insert(array $params)
    {
        $directory = (string)self::DIRECTORY_PROFILE;

        $cvName = (microtime(true)*10000)."_".$params['cv']->getClientOriginalName();
        $avatarName = $params['avatar']->getClientOriginalName();
        $this->fileService->uploadFile($directory, $cvName, file_get_contents($params['cv']));
        $this->fileService->uploadFile($directory, $avatarName, file_get_contents($params['cv']));
        $params['cv'] = $this->fileService->getUrl($directory . '/' . $cvName);
        $params['avatar'] = $this->fileService->getUrl($directory . '/' . $avatarName);

        $data = $this->employeeRepo->insertEmployee($params);

        if(isset($params['technologies'])) {
            foreach (explode(",", $params['technologies']) as $technology_id) {
                $this->employeeTechnologyRepo->insertTechnology(['technology_id' => (int)$technology_id, 'employee_id' => (int)$data['id']]);
            }
        }
        return $data;
    }

    /**
     * Show employee
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getById(int $id)
    {
        return $this->employeeRepo->getById($id);
    }

    /**
     * Show employee
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll(array $params)
    {
        $limit = $params['limit'] ?? config('pagination.pagination_limit_default');
        $sortDirection = $params['sortDirection'] ?? config('sort.sort_direction_default');
        $fieldSort = $params['fieldSort'] ?? config('sort.field_sort_default');

        return $this->employeeRepo->findAll((int)$limit, $params, $fieldSort, $sortDirection);
    }

    /**
     * Delete employee by id
     *
     * @param array $ids
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function setStatus(array $ids)
    {
        $data = [];
        foreach ($ids as $id) {
            array_push($data, $this->employeeRepo->setStatusById($id));
        }
        return $data;
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
    public function update(int $id, array $params, string $technologyId)
    {
        $directory = (string)self::DIRECTORY_PROFILE;
        if(!is_string($params['avatar'])) {
            $filenameImg = (microtime(true)*10000)."_".$params['avatar']->getClientOriginalName();
            $this->fileService->uploadFile($directory, $filenameImg, file_get_contents($params['avatar']));
            $params['avatar'] = $this->fileService->getUrl($directory . '/' . $filenameImg);
        }
        
        if(!is_string($params['avatar'])) {
            $filenameCV = (microtime(true)*10000)."_".$params['cv']->getClientOriginalName();
            $this->fileService->uploadFile($directory, $filenameCV, file_get_contents($params['cv']));           
            $params['cv'] = $this->fileService->getUrl($directory . '/' . $filenameCV);
        }
        
        $this->employeeTechnologyRepo->deleteTechnology($id);
        if (isset($technologyId)) {
            foreach (explode(",", $technologyId) as $technology_id) {
                $this->employeeTechnologyRepo->insertTechnology(['technology_id' => (int)$technology_id, 'employee_id' => (int)$id]);
            }
        }
        return $this->employeeRepo->updateEmployee($id, $params, $technologyId);
    }

    /**
     * Get total employee
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTotalEmployees()
    {
        return $this->employeeRepo->getTotalEmployees();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function groupByStatus()
    {
        return $this->employeeRepo->groupByStatus();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function groupByPosition($year)
    {
        return $this->employeeRepo->groupByPosition($year);
    }
}
