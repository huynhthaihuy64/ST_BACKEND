<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'position_id',
        'name',
        'email',
        'address',
        'phone',
        'birthday',
        'experience',
        'cv',
        'description',
        'avatar',
        'status',
        'start_date',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function positions()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    public function technologies(){
        return $this->belongsToMany(Technology::class, 'employees_technologies');
    }
    
    public function modelFilter()
    {
        return $this->provideFilter(\App\ModelFilters\EmployeeFilter::class);
    }
}
