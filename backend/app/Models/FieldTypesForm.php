<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldTypesForm extends Model
{
    use HasFactory;
    protected $fillable = [
        'typeform_id',
        'label',
        'description',
        'value',
        'index',
        'require',
        'type',
        'name'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function forms()
    {
        return $this->belongsTo('App\Models\Form', 'typeform_id', 'id');
    }
}
