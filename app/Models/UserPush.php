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
        'user_id',
        'player_id',
        'device_model',
        'device_type',
        'identifier',
        'response',
        'language'
    ];

    /*
    * RELATIONS
    */

    public function Users(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
