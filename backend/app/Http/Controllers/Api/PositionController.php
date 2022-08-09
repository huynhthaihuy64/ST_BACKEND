<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Common\ResponseService;
use App\Services\PositionService;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * @var PositionService
     */
    private $positionService;

    /**
     * @var ResponseService
     */
    private $responseService;

    /**
     * @param PositionService $positionService
     * @param ResponseService $responseService
     */
    public function __construct(PositionService $positionService, ResponseService $responseService)
    {
        $this->positionService = $positionService;
        $this->responseService = $responseService;
    }

    /**
     * @param Request $request
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function index(Request $request)
    {
        return $this->responseService->response(
            true,
            $this->positionService->findAll($request->all()),
            __('messages.get.success', ['name' => 'position'])
        );
    }
}
