<?php


namespace App\Repositories;

use App\Models\Campaign;
use App\Enums\CampaignStatusType;
use App\Repositories\CommonRepo;
class CampaignRepo extends EloquentRepo
{

    /**
     * @var CommonRepo
     */
    private $commonRepo;

    public function getModel()
    {
        return Campaign::class;
    }

    /**
     * Create campaign
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function insertCampaign(array $params)
    {
        $params['status'] = CampaignStatusType::PUBLIC;
        return $this->model->create($params);
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
     * Show campaign
     * Show data campaign
     *
     * @param int $limit
     * @param array $params
     * @param string $fieldSort
     * @param asc|desc $sortDirection
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAll(int $limit, array $params, $fieldSort, string $sortDirection)
    {
        $query = $this->model;
        $query = $query->filter($params)
            ->with(
                array(
                    'positions' => function ($querySlect) {
                        $querySlect->select('position_id', 'name');
                    },
                    'technologies' => function ($querySlect) {
                        $querySlect->select('technology_id', 'name');
                    },
                )
            );

        return $this->commonRepo->queryTable($query, $limit, $fieldSort, $sortDirection);
    }

    /**
     * Show data campaign
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getById(int $id)
    {
        return $this->model->with(
            array(
                'positions' => function ($querySlect) {
                    $querySlect->select('position_id', 'name');
                },
                'technologies' => function ($querySlect) {
                    $querySlect->select('technology_id', 'name');
                },
            )
        )->where('id', $id)->first();
    }

    /**
     * Show data campaign
     *
     * @param int $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByStatus(int $limit)
    {
        return $this->model->with(
            array(
                'positions' => function ($querySlect) {
                    $querySlect->select('position_id', 'name');
                },
                'technologies' => function ($querySlect) {
                    $querySlect->select('technology_id', 'name');
                },
            )
        )->where('status', CampaignStatusType::PUBLIC)->paginate($limit);
    }

    /**
     * View campaign chart
     *
     * @param string $month
     * @param string $year
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCampaignByDate(string $month, string $year)
    {
        return $this->model->whereMonth('created_at', '=', $month)
            ->whereYear('created_at', '=', $year)
            ->get()
            ->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function groupByStatus()
    {
        return $this->commonRepo->groupByStatus($this->model);
    }

    /**
     * Get total public campaign
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTotalCampaigns(){
        return $this->model->where('status', CampaignStatusType::PUBLIC)->get()->count();
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
    public function updateCampaign(int $id, array $params)
    {
        $query = $this->model
            ->with(
                array('technologies' => function ($querySlect) {
                    $querySlect->select('technology_id', 'name');
                })
            )
            ->with(
                array('positions' => function ($querySlect) {
                    $querySlect->select('position_id', 'name');
                })
            );

        return $this->commonRepo->updateTable($query, $params, $id);
    }

    /**
     * Delete campaign by id
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function deleteById(int $id)
    {
        return $this->model->where('id', $id)->delete();
    }
}
