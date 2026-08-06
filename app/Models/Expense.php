<?php

namespace App\Models;

class Expense extends Accounting
{
    protected $attributes = [
        'type' => 'expenses',
    ];

    protected static function booted()
    {
        static::addGlobalScope('type', function ($query) {
            $query->where('type', 'expenses');
        });
    }
}
