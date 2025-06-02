<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $fillable = [
        'company_name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'postal_code',
        'tax_number',
        'logo'
    ];
}
