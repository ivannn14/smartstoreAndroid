<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    protected $fillable = [
        'site_name',
        'site_description',
        'default_currency',
        'timezone',
        'date_format',
        'footer_text',
        'maintenance_mode'
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
    ];
}
