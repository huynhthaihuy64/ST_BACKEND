<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\CampaignRequest;
use App\Services\CampaignService;
use App\Http\Requests\Campaign\ListRequest;
use App\Http\Requests\Campaign\CampaignCreateRequest;
use App\Services\Common\ResponseService;
use Illuminate\Http\Request;
use App\Enums\CampaignStatusType;
use Exception;
use Throwable;

class CampaignController extends Controller
{
    /**
     * @var CampaignService
     */
    private $campaignService;

    /**
     * @var ResponseService
     */
    private $responseService;

    /**
     * @param CampaignService $campaignService
     * @param ResponseService $responseService
     */
    public function __construct(CampaignService $campaignService, ResponseService $responseService)
    {
        $this->campaignService = $campaignService;
        $this->responseService = $responseService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CampaignCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CampaignCreateRequest $request)
    {
        $params = $request->validated();
        $data = $this->campaignService->insert($params);

        return $this->responseService->response(
            $data ? true : false,
            $data,
            __('messages.create.success', ['name' => 'campaign'])
        );
    }

    /**
     * Show data campaign by id
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $data = $this->campaignService->getById($id);

        return $this->responseService->response(
            true,
            $data,
            $data ? __('messages.get.success', ['name' => 'campaign']) :
                __('messages.get.fail', ['name' => 'campaign'])
        );
    }

    /**
     * Show data campaign active
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function showActiveCampaign(Request $request)
    {
        $data = $this->campaignService->getByStatus($request->all());

        return $this->responseService->response(
            true,
            $data,
            $data ? __('messages.get.success', ['name' => 'campaign']) :
                __('messages.get.fail', ['name' => 'campaign'])
        );
    }

        /**
     * Show data campaign active
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function showActiveCampaignDetail(int $id)
    {
        $data = $this->campaignService->getActiveCampaignDetail($id);

        return $this->responseService->response(
            true,
            $data,
            $data ? __('messages.get.success', ['name' => 'campaign']) :
                __('messages.get.fail', ['name' => 'campaign'])
        );
    }

    /**
     * @param ListRequest $request
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function index(ListRequest $request)
    {
        return $this->responseService->response(
            true,
            $this->campaignService->findAll($request->only('limit', 'fieldSort', 'sortDirection', 'name', 'status', 'start', 'end', 'positions')),
            __('messages.get.success', ['name' => 'campaign'])
        );
    }

    /**
     * View campaign chart
     *
     * @param string $year
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function getCampaignChart(string $year)
    {
        try {
            return $this->responseService->response(
                $status = true,
                $data = $this->campaignService->getCampaignByYear($year),
                $message = __('messages.show.success', ['name' => 'campaign'])
            );
        } catch (Exception $e) {
            $status = false;
            [];
            $message = $e->getMessage();
        }
    }

    /**
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function groupByStatus()
    {
        return $this->responseService->response(
            true,
            $this->campaignService->groupByStatus(),
            __('messages.get.success', ['name' => 'total by status'])
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CampaignRequest $request
     * @param  int  $id
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function update(CampaignRequest $request, $id)
    {
        $params = $request->only('name', 'address', 'quantity', 'start_at', 'end_at', 'status', 'description', 'image', 'sheet_id');
        $technologyId = $request->technologies;
        $positionId = $request->positions;
        $data = $this->campaignService->update($id, $params, $technologyId, $positionId);

        return $this->responseService->response(
            $data ? true : false,
            $data,
            $data ? __('messages.update.success', ['name' => 'campaign']) :
                __('messages.update.fail', ['name' => 'campaign'])
        );
    }

    /**
     * Get campaign status
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function getCampaignStatusType()
    {
        $data = CampaignStatusType::asArray();
        $result = [];
        foreach ($data as $key => $value) {
            array_push($result, ['value' => $key, 'text' => $value]);
        };
        return $this->responseService->response(
            true,
            $result,
            __('messages.get.success', ['name' => 'campaign status'])
        );
    }

    /**
     * Delete campagin
     *
     * @param Request $request
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function delete(Request $request)
    {
        $data = $this->campaignService->delete($request->ids);

        return $this->responseService->response(
            $data ? true : false,
            $data,
            __('messages.get.success', ['name' => 'campaign status'])
        );
    }
}
