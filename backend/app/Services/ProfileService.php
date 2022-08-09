<?php

namespace App\Services;

use App\Services\Common\FileService;
use App\Enums\DateType;
use App\Repositories\ProfileRepo;
use Carbon\Carbon;
use App\Repositories\ProfilesTechnologyRepo;

/**
 * Class ProfileService
 * @package App\Services
 */
class ProfileService
{
    /**
     * @var const PROFILE
     */
    public const DIRECTORY_PROFILE = "Profile";

    /**
     * @var ProfileRepo
     */
    private $profileRepo;

    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @var ProfilesTechnologyRepo
     */
    private $profilesTechnologyRepo;

    /**
     * @param ProfileRepo $profileRepo
     */
    public function __construct(ProfileRepo $profileRepo, ProfilesTechnologyRepo $profilesTechnologyRepo)
    {
        $this->profileRepo = $profileRepo;
        $this->fileService = new FileService(config('storage.disk'));
        $this->profilesTechnologyRepo = $profilesTechnologyRepo;
    }

    /**
     * Create profile
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function insert(array $params)
    {
        $directory = (string)self::DIRECTORY_PROFILE;

        if (!is_string($params['avatar'])) {
            $fileNameAvatar = (microtime(true) * 10000) . "_" . $params['avatar']->getClientOriginalName();
            $this->fileService->uploadFile($directory, $fileNameAvatar, file_get_contents($params['avatar']));
            $params['avatar'] = $this->fileService->getUrl($directory . "/" . $fileNameAvatar);
        } else {
            $params['avatar'] = '';
        }

        $fileNameCv = (microtime(true) * 10000) . "_" . $params['cv']->getClientOriginalName();
        $this->fileService->uploadFile($directory, $fileNameCv, file_get_contents($params['cv']));
        $params['cv'] = $this->fileService->getUrl($directory . "/" . $fileNameCv);

        $data = $this->profileRepo->insertProfile($params);
        $data['other'] = json_decode(base64_decode($data['other']));

        return $data;
    }

    /**
     * Create profile
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function submitTypeform(array $params)
    {
        $directory = (string)self::DIRECTORY_PROFILE;
        $name = (microtime(true)*10000)."_".$params['cv']->getClientOriginalName();
        $this->fileService->uploadFile($directory, $name, file_get_contents($params['cv']));

        $params['cv'] = $this->fileService->getUrl($directory."/".$name);

        $data = $this->profileRepo->insertProfile($params);
        $data['other'] = json_decode(base64_decode($data['other']));

        if (isset($params['technologies'])) {
            foreach ($params['technologies'] as $technology_id) {
                $this->profilesTechnologyRepo->insertTechnology(['technology_id' => (int)$technology_id, 'profile_id' => (int)$data['id']]);
            }
        }
        return $data;
    }

    /**
     * Show data profile by id
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findOne(int $id)
    {
        $data = $this->profileRepo->find($id);
        $data['other'] = json_decode(base64_decode($data['other']));

        return $data;
    }

    /**
     * Show data profile
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

        return $this->profileRepo->findAll((int)$limit, $params, $fieldSort, $sortDirection);
    }

    /**
     * Show profile by campagin id
     *
     * @param array $params
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listByCampaignId(array $params, int $campaign_id)
    {
        $limit = $params['limit'] ?? config('pagination.pagination_limit_default');
        $sortDirection = $params['sortDirection'] ?? config('sort.sort_direction_default');
        $fieldSort = $params['fieldSort'] ?? config('sort.field_sort_default');

        return $this->profileRepo->listByCampaignId((int)$limit, $campaign_id, $params, $fieldSort, $sortDirection);
    }

    /**
     * Get list status by profile
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function groupByStatus()
    {
        return $this->profileRepo->groupByStatus();
    }

    /**
     * Get toltal profile
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTotalProfiles()
    {
        return $this->profileRepo->getTotalProfiles();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param array $params
     * @param string $technologyId
     *
     * @return  Illuminate\Database\Eloquent\Model
     */
    public function update(int $id, array $params, string $technologyId)
    {
        $directory = (string)self::DIRECTORY_PROFILE;

        if(!is_string($params['avatar'])) {
        $filenameImg = (microtime(true)*10000)."_".$params['avatar']->getClientOriginalName();
        $this->fileService->uploadFile($directory, $filenameImg, file_get_contents($params['avatar']));
        $params['avatar'] = $this->fileService->getUrl($directory . '/' . $filenameImg);
        }
        if(!is_string($params['cv'])) {
            $filenameCV = (microtime(true)*10000)."_".$params['cv']->getClientOriginalName();
            $this->fileService->uploadFile($directory, $filenameCV, file_get_contents($params['cv']));
            $params['cv'] = $this->fileService->getUrl($directory . '/' . $filenameCV);
        }

        $this->profilesTechnologyRepo->deleteTechnology($id);

        if (isset($technologyId)) {
            foreach (explode(",", $technologyId) as $technology_id) {
                $this->profilesTechnologyRepo->insertTechnology(['technology_id' => (int)$technology_id, 'profile_id' => (int)$id]);
            }
        }

        return $this->profileRepo->updateProfile($id, $params, $technologyId);
    }

    /**
     * Update status by enum
     *
     * @param int $id
     * @param string $status
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function setStatus(int $id, string $status)
    {
        return $this->profileRepo->setStatus($id, $status);
    }

    /**
     * Get percent with campaign cv / quantity
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function groupByPercent()
    {
        return $this->profileRepo->groupByPercent();
    }

    /**
     * Get Total Profile By Month
     *
     * @param string $year
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProfileByYear(string $year)
    {
        $listMonth = DateType::getValues();
        $months = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
        $dataOfProfile = [];
        $monthOfProfile = [];
        $nowMonth = Carbon::now()->month;
        $nowYear = (string)Carbon::now()->year;

        switch ($year) {
            case ($year < $nowYear):
                foreach ($months as $month) {
                    $dataOfProfile[] = $this->profileRepo->getProfileByDate($month, $year);
                }
                foreach ($listMonth as $month) {
                    $monthOfProfile[] = DateType::getDescription($month);
                }
                break;

            case ($year === $nowYear):
                foreach (array_slice($months, 0, $nowMonth) as $month) {
                    $dataOfProfile[] = $this->profileRepo->getProfileByDate($month, $year);
                }
                foreach (array_slice($listMonth, 0, $nowMonth) as $month) {
                    $monthOfProfile[] = DateType::getDescription($month);
                }
                break;

            default:
                break;
        }
        return compact('monthOfProfile', 'dataOfProfile');
    }
}
