<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Enums\ProfileStatusType;
use App\Repositories\CommonRepo;
use App\Services\Common\SheetService;

class ProfileRepo extends EloquentRepo
{

    /**
     * @var CommonRepo
     */
    private $commonRepo;

    public function getModel()
    {
        return Profile::class;
    }

    /**
     * @param CommonRepo $commonRepo
     */
    public function __construct(CommonRepo $commonRepo)
    {
        $this->commonRepo = $commonRepo;
        parent::__construct();
    }

    /**
     * Show profile
     * Create profile
     * 
     * @param array $params
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */    
    public function insertProfile(array $params)
    {
        $params['status'] = ProfileStatusType::NEWS;
        $params['other'] = base64_encode(json_encode($params['other']));

        (new SheetService())->appendSheets([
            [
                $params['name'],
                $params['email'],
                $params['phone'],
                $params['status'],
                $params['cv'],
                $params['avatar']
            ]
        ]);
        
        return $this->model->create($params);
    }

    /**
     * Get list profile by campaign id.
     * 
     * @param int $limit
     * @param int $campaign_id
     * @param array $params
     * @param string $fieldSort
     * @param asc|desc $sortDirection
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listByCampaignId(int $limit, int $campaign_id, array $params, string $fieldSort, string $sortDirection)
    {
        $query = $this->model->where('campaign_id', '=', $campaign_id);
        $query = $this->filter($query, $params);

        return $this->commonRepo->queryTable($query, $limit, $fieldSort, $sortDirection);
    }

    /**
     * Show profile
     * Show data resource in storage.
     * 
     * @param int $limit
     * @param array $params
     * @param string $fieldSort
     * @param asc|desc $sortDirection
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll(int $limit, array $params, string $fieldSort, string $sortDirection)
    {
        $query = $this->model;
        $query = $this->filter($query, $params);

        return $this->commonRepo->queryTable($query, $limit, $fieldSort, $sortDirection);
    }

    /** 
     * Store a newly created resource in storage.
     * 
     * @param Model $model
     * @param array $request
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function create($request)
    {
        return  $this->model->updateOrCreate($request);
    }

    /**
     * Filter profile
     * Get Profile By Month,Year
     * 
     * @param Model $model
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProfilesByMonthYear()
    {
        return $this->model->raw('YEAR(created_at) year, MONTH(created_at) month')->groupby('year','month')->get();
    }

    /**
     * filter profile
     * 
     * @param Model $model
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filter($model, array $params)
    {
        return $model->filter($params)
            ->with(
                array(
                    'campaigns' => function ($querySlect) {
                        $querySlect->select('id', 'name');
                    },
                )
            );
    }

    /**
     * Get list status by profile
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function groupByStatus()
    {
        return $this->commonRepo->groupByStatus($this->model);
    }

    /**
     * Get toltal new profile
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTotalProfiles(){
        return $this->model->where('status', ProfileStatusType::NEWS)->get()->count();
    }

    /*
     * Update status by enum.
     * 
     * @param int $id
     * @param string $status
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function setStatus(int $id, string $status)
    {
        try {
            $statusReq = ProfileStatusType::getValue($status);
            $this->model->where('id', $id)->update(['status' => $statusReq]);

            return ['status' => true, 'message' => __('messages.update.success', ['name' => 'status'])];
        } catch (\Throwable $th) {
            return ['status' => false, 'message' => __('messages.update.fail', ['name' => 'status'])];
        }
    }

    /**
     * Get percent with campaign cv / quantity
     * 
     * View total CV from Profile and total Profile By Month
     * 
     * @param string $month
     * @param string $year
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProfileByDate(string $month, string $year)
    {
        return $this->model->whereMonth('created_at', '=', $month)
            ->whereYear('created_at', '=', $year)
            ->get()
            ->count();
    }

    /**
     * Get percent with campaign cv / quantity
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function groupByPercent()
    {
        return $this->model->join('campaigns', 'profiles.campaign_id', '=', 'campaigns.id')
            ->select('campaigns.name', $this->model::raw('round((COUNT(profiles.cv)*100/quantity),0) as percent'))
            ->groupBy('campaigns.name', 'quantity')->get();
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
    public function updateProfile(int $id, array $params, string $technologyId)
    {
        $this->model->find($id)->technologies()->sync($technologyId);
        $query = $this->model->with(
            array(
                'campaigns' => function ($querySlect) {
                    $querySlect->select('id', 'name');
                },
                'technologies' => function ($querySlect) {
                    $querySlect->select('technology_id', 'name');
                },
            )
        );

        (new SheetService())->updateSheets($id,[
            [
                $params['name'],
                $params['email'],
                $params['phone'],
                $params['status'],
                $params['cv'],
                $params['avatar']
            ]
        ]);

        return $this->commonRepo->updateTable($query, $params, $id);
    }
}
