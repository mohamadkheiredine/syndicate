<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersPush extends Model
{
    protected $table = 'users_push';

    protected $fillable = [
        'user_id',
        'player_id',
    ];

    public function syndicateUser()
    {
        return $this->belongsTo(SyndicateUser::class, 'user_id');
    }
}
