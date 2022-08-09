<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use EloquentFilter\Filterable;

class Profile extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'campaign_id',
        'email',
        'phone',
        'name',
        'status',
        'cv',
        'description',
        'avatar',
        'other'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function modelFilter()
    {
        return $this->provideFilter(\App\ModelFilters\ProfileFilter::class);
    }

    public function campaigns()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'profiles_technologies');
    }
}
