<?php

namespace App\Models;

class Income extends Accounting
{
    protected $attributes = [
        'type' => 'income',
    ];

    protected static function booted()
    {
        static::addGlobalScope('type', function ($query) {
            $query->where('type', 'income');
        });
    }
}
