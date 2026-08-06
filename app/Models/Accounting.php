<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accounting extends Model
{
    protected $table = 'accounting';

    protected $fillable = [
        'type',
        'amount',
        'title',
        'description',
        'payment_date',
    ];

    protected $casts = [
        'amount' => 'float',
        'payment_date' => 'datetime',
    ];
}
