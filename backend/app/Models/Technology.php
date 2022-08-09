<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaigns_technologies');
    }

    public function profiles()
    {
        return $this->belongsToMany(Profile::class, 'profiles_technologies');
    }
}
