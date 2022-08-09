<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;

class Campaign extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'name',
        'address',
        'quantity',
        'start_at',
        'end_at',
        'status',
        'description',
        'sheet_id',
        'slug',
        'image'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function modelFilter()
    {
        return $this->provideFilter(\App\ModelFilters\CampaignFilter::class);
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'campaigns_positions');
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'campaigns_technologies');
    }
}
