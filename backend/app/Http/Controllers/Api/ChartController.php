<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EmployeeService;
use App\Services\Common\ResponseService;
use App\Services\CampaignService;
use App\Services\ProfileService;
use Exception;

class ChartController extends Controller
{
    /**
     * @var ProfileService
     * @var ResponseService
     * @var EmployeeService
     * @var CampaignService
     */
    private $profileService;
    private $responseService;
    private $employeeService;
    private $campaignService;

    /**
     * @param ProfileService $profileService
     * @param ResponseService $responseService
     * @param EmployeeService $employeeService
     * @param CampaignService $campaignService
     */
    public function __construct(ProfileService $profileService, ResponseService $responseService, CampaignService $campaignService, EmployeeService $employeeService)
    {
        $this->profileService = $profileService;
        $this->responseService = $responseService;
        $this->campaignService = $campaignService;
        $this->employeeService = $employeeService;
    }

    /**
     * Get total new profile, employee, campaign
     * 
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function getTotalProfileEmployeeCampaign()
    {
        $profiles = $this->profileService->getTotalProfiles();
        $employees = $this->employeeService->getTotalEmployees();
        $campaigns = $this->campaignService->getTotalCampaigns();
        $data = ['Profile' => $profiles,'Employee' => $employees,'Campaign' => $campaigns];
        $message = $data === null ? __('messages.chart.fail', ['name' => 'profile']) : __('messages.chart.success', ['name' => 'chart']);
        $status = $data === null ? false : true;

        return $this->responseService->response($status, $data, $message);
    }

    /*
     * View campaign and cv chart
     * 
     * @param string $year
     * 
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function getTotalCvByCampaignWithYear(string $year)
    {
        $campaginsChart = $this->campaignService->getCampaignByYear($year);
        $cvChart = $this->profileService->getProfileByYear($year);
        $campaignCvChart = ['campaginsChart' => $campaginsChart, 'cvChart' => $cvChart];

        try {
            return $this->responseService->response(
                $status = true, 
                $campaignCvChart,
                $message = __('messages.show.success', ['name' => 'campaign-cv'])
            );
        }
        catch (Exception $e) {
            $status = false;
            [];
            $message = $e->getMessage();
        }
    }
}
