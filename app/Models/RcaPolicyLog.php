<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RcaPolicyLog extends Model
{
    protected $fillable = [
        'rca_customer_id',
        'offer_id',
        'policy_id',
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