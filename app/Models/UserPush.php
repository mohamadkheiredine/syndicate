<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPush extends Model
{
    protected $table = "user_push";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'users_id',
        'registration_id',
    ];

    /*
    * RELATIONS
    */

    public function syndicateUser(){
        return $this->belongsTo(SyndicateUser::class, 'users_id');
    }
}
