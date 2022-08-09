<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Common\ResponseService;
use App\Services\TechnologyService;

class TechnologyController extends Controller
{
    /**
     * @var TechnologyService
     */
    private $technologyService;

    /**
     * @var ResponseService
     */
    private $responseService;

    /**
     * @param TechnologyService $technologyService
     * @param ResponseService $responseService
     */
    public function __construct(TechnologyService $technologyService, ResponseService $responseService)
    {
        $this->technologyService = $technologyService;
        $this->responseService = $responseService;
    }

    /**
     * @param Request $request
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function index()
    {
        return $this->responseService->response(
            true,
            $this->technologyService->findAll(),
            __('messages.get.success', ['name' => 'technologies'])
        );
    }
}
