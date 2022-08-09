<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Illuminate\Database\Eloquent\Builder;

class CampaignFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function name($name)
    {
        return $this->whereLike('name', $name);
    }

    public function status($status)
    {
        return $this->whereIn('status', explode(',', $status));
    }

    public function start($start)
    {
        return $this->whereDate('start_at','=', $start);
    }

    public function end($end)
    {
        return $this->whereDate('end_at','=', $end);
    }

    public function positions($positionIds)
    {
        return $this->whereHas(
            'positions',
            fn (Builder $query) => $query->whereIn('positions.id', explode(',', $positionIds))
        );
    }
}
