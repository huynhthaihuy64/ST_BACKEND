<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Illuminate\Database\Eloquent\Builder;

class ProfileFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function phone($phone)
    {
        return $this->whereLike('phone', $phone);
    }

    public function status($status)
    {
        return $this->whereIn('status', explode(',', $status));
    }

    public function email($email)
    {
        return $this->whereLike('email', $email);
    }

    public function name($name)
    {
        return $this->whereLike('name', $name);
    }

    public function campaignName($campaignName)
    {
        return $this->whereHas(
            'campaigns',
            fn (Builder $query) => $query->whereLike('campaigns.name', $campaignName)
        );
    }
}
