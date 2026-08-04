<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SupportCategory extends Model
{
    protected $fillable = [
        'id',
        'title'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
