<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyndicateJoin extends Model
{
    protected $table = 'syndicate_join';

    public $timestamps = false;

    protected $fillable = [
        'syndicate_name',
        'syndicate_email',
    ];
}
