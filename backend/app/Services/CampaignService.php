<?php

namespace App\Services;

use App\Enums\DateType;
use App\Repositories\CampaignRepo;
use App\Services\Common\FileService;
use App\Services\Common\SlugService;
use App\Services\FormService;
use Carbon\Carbon;
use App\Repositories\CampaignsTechnologyRepo;
use App\Repositories\CampaignsPositionRepo;

/**
 * Class CampaignService
 * @package App\Services
 */
class CampaignService
{
    /**
     * @var const DIRECTORY_CAMPAIGN
     */
    public const DIRECTORY_CAMPAIGN = 'Campaign';

    /**
     * @var CampaignRepo
     */
    private $campaignRepo;

    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @var SlugService
     */
    private $slugService;

    /**
     * @var FormService
     */
    private $formService;

    /**
     * @var CampaignsTechnologyRepo
     */
    private $campaignsTechnologyRepo;

    /**
     * @var CampaignsPositionRepo
     */
    private $campaignsPositionRepo;

    /**
     * @param CampaignRepo $campaignRepo
     * @param SlugService $slugService
     * @param FormService $formService
     * @param CampaignsTechnologyRepo $campaignsTechnologyRepo
     * @param CampaignsPositionRepo $campaignsPositionRepo
     */
    public function __construct(CampaignRepo $campaignRepo, SlugService $slugService, FormService $formService, CampaignsTechnologyRepo $campaignsTechnologyRepo, CampaignsPositionRepo $campaignsPositionRepo)
    {
        $this->campaignRepo = $campaignRepo;
        $this->fileService = new FileService(config('storage.disk'));
        $this->slugService = $slugService;
        $this->formService = $formService;
        $this->campaignsTechnologyRepo = $campaignsTechnologyRepo;
        $this->campaignsPositionRepo = $campaignsPositionRepo;
    }

    /**
     * Create campaign
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function insert(array $params)
    {
        $directory = (string)self::DIRECTORY_CAMPAIGN;
        if (!is_string($params['image'])) {
            $filenameImg = (microtime(true)*10000)."_".$params['image']->getClientOriginalName();
            $this->fileService->uploadFile($directory, $filenameImg, file_get_contents($params['image']));
            $params['image'] = $this->fileService->getUrl($directory . '/' . $filenameImg);
        } else {
            $params['image'] = config('storage.campaign.background');
        }

        $params['slug'] = $this->slugService->getSlug($params['name']);

        $data = $this->campaignRepo->insertCampaign($params);

        $form['campaign_id'] = $data['id'];
        $form['image'] = config('storage.form.background');
        $this->formService->insert($form);
        if ($params['technologies'] != 'undefined') {
            foreach (explode(",", $params['technologies']) as $technology_id) {
                $this->campaignsTechnologyRepo->insertTechnology(['technology_id' => (int)$technology_id, 'campaign_id' => (int)$data['id']]);
            }
        }
        foreach (explode(",", $params['positions']) as $position_id) {
            $this->campaignsPositionRepo->insertPosition(['campaign_id' => (int)$data['id'], 'position_id' => (int)$position_id]);
        }
        return $data;
    }

    /**
     * Show all campaign
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getById(int $id)
    {
        return $this->campaignRepo->getById($id);
    }

    /**
     * Show all campaign
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll(array $params)
    {
        $limit = $params['limit'] ?? config('pagination.pagination_limit_default');
        $sortDirection = isset($params['sortDirection']) ? ($params['sortDirection'] == 'ascend' ? 'asc' : 'desc') : config('sort.sort_direction_default');
        $fieldSort = isset($params['sortDirection']) ? $params['fieldSort'] ?? config('sort.field_sort_default') : config('sort.field_sort_default');

        return $this->campaignRepo->findAll((int)$limit, $params, $fieldSort, $sortDirection);
    }

    /**
     * Show all campaign active
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByStatus(array $params)
    {
        $limit = $params['limit'] ?? config('pagination.pagination_limit_default');

        return $this->campaignRepo->getByStatus((int)$limit);
    }

    /**
     * Show campaign active detail
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCampaignDetail(int $id)
    {
        return $this->campaignRepo->getById($id);
    }

    /**
     * View campaign chart
     *
     * @param string $year
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCampaignByYear(string $year)
    {
        $listMonth = DateType::getValues();
        $months = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
        $dataOfCampaign = [];
        $monthOfCampaign = [];
        $nowMonth = Carbon::now()->month;
        $nowYear = (string)Carbon::now()->year;

        switch ($year) {
            case ($year < $nowYear):
                foreach ($months as $month) {
                    $dataOfCampaign[] = $this->campaignRepo->getCampaignByDate($month, $year);
                }
                foreach ($listMonth as $month) {
                    $monthOfCampaign[] = DateType::getDescription($month);
                }
                break;

            case ($year === $nowYear):
                foreach (array_slice($months, 0, $nowMonth) as $month) {
                    $dataOfCampaign[] = $this->campaignRepo->getCampaignByDate($month, $year);
                }
                foreach (array_slice($listMonth, 0, $nowMonth) as $month) {
                    $monthOfCampaign[] = DateType::getDescription($month);
                }
                break;

            default:
                break;
        }
        return compact('monthOfCampaign', 'dataOfCampaign');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function groupByStatus()
    {
        return $this->campaignRepo->groupByStatus();
    }

    /**
     * Get total campaign
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTotalCampaigns()
    {
        return $this->campaignRepo->getTotalCampaigns();
    }

    /*
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param array $params
     * @param string $technologyId
     * @param string $positionId
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function update(int $id, array $params, $technologyId, $positionId)
    {
        $directory = (string)self::DIRECTORY_CAMPAIGN;
        if(!is_string($params['image'])) {
            $filenameImg = (microtime(true)*10000)."_".$params['image']->getClientOriginalName();
            $this->fileService->uploadFile($directory, $filenameImg, file_get_contents($params['image']));
            $params['image'] = $this->fileService->getUrl($directory . '/' . $filenameImg);
        }

        $this->campaignsTechnologyRepo->deleteTechnology($id);
        $this->campaignsPositionRepo->deletePosition($id);

        if (isset($technologyId)) {
            foreach (explode(",", $technologyId) as $technology_id) {
                $this->campaignsTechnologyRepo->insertTechnology(['technology_id' => (int)$technology_id, 'campaign_id' => (int)$id]);
            }
        }

        foreach (explode(",", $positionId) as $position_id) {
            $this->campaignsPositionRepo->insertPosition(['campaign_id' => (int)$id, 'position_id' => (int)$position_id]);
        }
        return $this->campaignRepo->updateCampaign($id, $params, $technologyId, $positionId);
    }

    /**
     * Delete campagin
     *
     * @param array $request
     *
     * @return array $data
     */
    public function delete(array $ids)
    {
        $data = [];
        foreach ($ids as $id) {
            array_push($data, $this->campaignRepo->deleteById((int)$id));
        }

        return $data;
    }
}
