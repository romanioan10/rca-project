<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RcaOfferLog extends Model
{
    protected $fillable = [
        'request_payload',
        'response_payload',
        'status',
        'error_message',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
    ];
}