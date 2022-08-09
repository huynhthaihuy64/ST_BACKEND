<?php

namespace App\Repositories;

use App\Models\CampaignsTechnology;

class CampaignsTechnologyRepo extends EloquentRepo
{
    public function getModel()
    {
        return CampaignsTechnology::class;
    }

    /**
     * Create employee technology
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function insertTechnology(array $params)
    {
        return $this->model->create($params);
    }

    public function deleteTechnology(int $id)
    {
        return $this->model->where('campaign_id', $id)->delete();
    }

}
