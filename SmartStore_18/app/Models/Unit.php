<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'base_unit',
        'operator',
        'operation_value',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function baseUnitRelation()
    {
        return $this->belongsTo(Unit::class, 'base_unit');
    }
}
