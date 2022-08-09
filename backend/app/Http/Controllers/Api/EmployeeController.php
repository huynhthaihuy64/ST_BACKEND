<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EmployeeService;
use App\Http\Requests\Employee\ListRequest;
use App\Http\Requests\Employee\EmployeeCreateRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Http\Requests\Employee\EmployeeDeleteRequest;
use App\Services\Common\ResponseService;
use App\Enums\EmployeeStatusType;

class EmployeeController extends Controller
{
    /**
     * @var EmployeeService
     */
    private $employeeService;

    /**
     * @var ResponseService
     */
    private $responseService;

    /**
     * @param EmployeeService $employeeService
     * @param ResponseService $responseService
     */
    public function __construct(EmployeeService $employeeService, ResponseService $responseService)
    {
        $this->employeeService = $employeeService;
        $this->responseService = $responseService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EmployeeCreateRequest $request
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function store(EmployeeCreateRequest $request)
    {
        $params = $request->validated();
        $data = $this->employeeService->insert($params);

        return $this->responseService->response(
            $data ? true : false,
            $data,
            __('messages.create.success', ['name' => 'employee'])
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function show(int $id)
    {
        $data = $this->employeeService->getById($id);

        return $this->responseService->response(
            true,
            $data,
            $data ? __('messages.get.success', ['name' => 'employee']) :
                __('messages.get.fail', ['name' => 'employee'])
        );
    }

    /**
     * Show employee
     *
     * @param ListRequest $request
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function index(ListRequest $request)
    {
        return $this->responseService->response(
            true,
            $this->employeeService->findAll($request->all()),
            __('messages.get.success', ['name' => 'employee'])
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  EmployeeDeleteRequest $request
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function setStatus(EmployeeDeleteRequest $request)
    {
        $ids = $request->ids;
        $data = $this->employeeService->setStatus($ids);

        return $this->responseService->response(
            $data ? true : false,
            $data,
            $data ? __('messages.delete.success', ['name' => 'employee']) :
                __('messages.delete.fail', ['name' => 'employee'])
        );
    }

    /**
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function groupByStatus()
    {
        return $this->responseService->response(
            true,
            $this->employeeService->groupByStatus(),
            __('messages.get.success', ['name' => 'total by status'])
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EmployeeUpdateRequest $request
     * @param  int  $id
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function update(EmployeeUpdateRequest $request, int $id)
    {
        $params = $request->only('email', 'phone', 'birthday', 'status', 'cv', 'description', 'avatar', 'experience', 'name', 'address');
        $technologyId = $request->technologies;
        $data = $this->employeeService->update($id, $params, $technologyId);

        return $this->responseService->response(
            $data ? true : false,
            $data,
            $data ? __('messages.update.success', ['name' => 'employee']) :
                __('messages.update.fail', ['name' => 'employee'])
        );
    }

    /**
     * @param string $year
     * 
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function groupByPosition(string $year)
    {
        return $this->responseService->response(
            true,
            $this->employeeService->groupByPosition($year),
            __('messages.get.success', ['name' => 'total by position'])
        );
    }

    /**
     * Get employee status
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function getEmployeeStatusType()
    {
        $data = EmployeeStatusType::asArray();
        $result = [];
        foreach ($data as $key => $value) {
            array_push($result, ['value' => $key, 'text' => $value]);
        };
        return $this->responseService->response(
            true,
            $result,
            __('messages.get.success', ['name' => 'employee status'])
        );
    }
}
