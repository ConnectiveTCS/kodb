<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    //
    protected $fillable = [
        'user_id',
        'partner_type',
        'first_name',
        'last_name',
        'email',
        'phone',
        'company_name',
        'company_website',
        'type_of_support',
        'status',
        'country',
        'city',
        'logo'
    ];
}
