<?php

namespace App\Repositories;

use App\Models\CampaignsPosition;

class CampaignsPositionRepo extends EloquentRepo
{
    public function getModel()
    {
        return CampaignsPosition::class;
    }

    /**
     * Create camapign position
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function insertPosition(array $params)
    {
        return $this->model->create($params);
    }

    public function deletePosition(int $id)
    {
        return $this->model->where('campaign_id', $id)->delete();
    }

}
