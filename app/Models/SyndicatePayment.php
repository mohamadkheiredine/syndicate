<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyndicatePayment extends Model
{
    protected $table = 'syndicate_payments';

    protected $fillable = [
        'payment_year',
        'payment_amount',
        'status',
        'publish_status',
    ];

    protected $hidden = [
        'status',
        'publish_status',
    ];

    protected $casts = [
        'payment_amount' => 'float',
    ];
}
