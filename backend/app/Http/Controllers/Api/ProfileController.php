<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProfileService;
use App\Services\Common\ResponseService;
use App\Http\Requests\Profile\ListRequest;
use App\Http\Requests\Profile\ProfileCreateRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Http\Requests\Profile\StatusRequest;
use Exception;
use App\Enums\ProfileStatusType;

class ProfileController extends Controller
{
    /**
     * @var ProfileService
     */
    private $profileService;

    /**
     * @var ResponseService
     */
    private $responseService;

    /**
     * @param ProfileService $profileService
     * @param ResponseService $responseService
     */
    public function __construct(ProfileService $profileService, ResponseService $responseService)
    {
        $this->profileService = $profileService;
        $this->responseService = $responseService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function store(ProfileCreateRequest $request)
    {
        $params = $request->only('campaign_id', 'email', 'avatar', 'phone', 'description', 'cv', 'name', 'status', 'technologies');
        $params['other'] = $request->except('campaign_id', 'email', 'avatar', 'phone', 'description', 'cv', 'name', 'status', 'technologies');

        $data = $this->profileService->insert($params);

        return $this->responseService->response(
            true,
            $data,
            __('messages.create.success',['name' => 'profile'])
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProfileCreateRequest $request
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function submitTypeform(ProfileCreateRequest $request)
    {
        $params = $request->only('campaign_id', 'email', 'avatar', 'phone', 'description', 'cv', 'name', 'status', 'technologies');
        $params['other'] = $request->except('campaign_id', 'email', 'avatar', 'phone', 'description', 'cv', 'name', 'status', 'technologies');
        $params['avatar'] = $params['avatar'] ?? "";
        $data = $this->profileService->submitTypeform($params);
        return $this->responseService->response(
            true,
            $data,
            __('messages.create.success',['name' => 'profile'])
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
        return $this->responseService->response(
            true,
            $this->profileService->findOne($id),
            __('messages.get.success', ['name' => 'profile'])
        );
    }

    /**
     * Show profile
     *
     * @param ListRequest $request
     * @param int $campaign_id
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function listByCampaignId(ListRequest $request, int $campaign_id)
    {
        return $this->responseService->response(
            true,
            $this->profileService->listByCampaignId($request->all(), $campaign_id),
            __('messages.get.success', ['name' => 'profile'])
        );
    }

    /**
     * Show data profile
     *
     * @param ListRequest $request
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function index(ListRequest $request)
    {
        return $this->responseService->response(
            true,
            $this->profileService->findAll($request->all()),
            __('messages.get.success', ['name' => 'profile'])
        );
    }

    /**
     * Get list status by profile
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function groupByStatus()
    {
        return $this->responseService->response(
            true,
            $this->profileService->groupByStatus(),
            __('messages.get.success', ['name' => 'total by status'])
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProfileRequest $request
     * @param int $id
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function update(ProfileUpdateRequest $request, int $id)
    {
        $params = $request->only('email','phone','status','cv','description','avatar','other','name');
        $technologyId = $request->technologies;
        $data = $this->profileService->update($id, $params, $technologyId);

        return $this->responseService->response(
            true,
            $data,
            $data ? __('messages.update.success', ['name' => 'profile']) :
            __('messages.update.fail', ['name' => 'profile'])
        );
    }

    /**
     *  Update status by enum.
     *
     * @param  StatusRequest  $request
     * @param int $id
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function setStatus(StatusRequest $request, int $id)
    {
        $data = $this->profileService->setStatus($id, $request->all()['status']);

        return $this->responseService->response(
            $data['status'],
            [],
            $data['message']
        );
    }

    /**
     * Get percent with campaign cv / quantity
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function groupByPercent()
    {
        return $this->responseService->response(
            true,
            $this->profileService->groupByPercent(),
            __('messages.get.success', ['name' => 'campaign cv by percent'])
        );
    }

    /**
     * Get Total Profile By Month
     *
     * @param string $year
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function getProfileChart(string $year){
        try {
            return $this->responseService->response(
                $status = true,
                $data = $this->profileService->getProfileByYear($year),
                $message = __('messages.show.success', ['name' => 'profile'])
            );
        }
        catch (Exception $e) {
            $status = false;
            [];
            $message = $e->getMessage();
        }
    }

    /**
     * Get profile status
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function getProfileStatusType()
    {
        $data = ProfileStatusType::asArray();
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
}
