<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RcaCustomer extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'tax_id',
        'email',
        'mobile_number',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}