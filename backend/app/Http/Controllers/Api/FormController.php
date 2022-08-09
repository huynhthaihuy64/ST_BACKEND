<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\FormRepo;
use App\Services\FormService;
use App\Services\Common\ResponseService;
use App\Http\Requests\Form\FormUpdateRequest;

class FormController extends Controller
{
    /**
     * @var FormService
     */
    private $formService;

    /**
     * @var FormRepo
     */
    private $formRepo;

    /**
     * @var ResponseService
     */
    private $responseService;

    /**
     * @param FormService $formService
     * @param FormRepo $formRepo
     * @param ResponseService $responseService
     */
    public function __construct(FormService $formService, FormRepo $formRepo, ResponseService $responseService)
    {
        $this->formService = $formService;
        $this->responseService = $responseService;
        $this->formRepo = $formRepo;
    }

    /**
     * Show data typeform
     * 
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function index()
    {
        $data = $this->formService->get();
        return $this->responseService->response(
            true,
            $data,
            __('messages.get.success', ['name' => 'forms'])
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
        $data = $this->formService->getById($id);

        return $this->responseService->response(
            true,
            $data,
            $data ? __('messages.get.success', ['name' => 'campaign']) :
                __('messages.get.fail', ['name' => 'campaign'])
        );
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param FormUpdateRequest $request
     * @param int $id
     * 
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function update(FormUpdateRequest $request, int $id)
    {
        $data = $this->formService->update($request->all(), $id);
        return $this->responseService->response(
            $data ? true : false,
            $data,
            $data ? __('messages.update.success', ['name' => 'campaign']) :
                __('messages.update.fail', ['name' => 'campaign'])
        );
    }
}
