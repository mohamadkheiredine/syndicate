<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
	protected $fillable = [
        'android_app',
        'apple_app',
        'title',
        'text',
        'android_version',
        'force_update_android',
        'ios_version',
        'force_update_ios'
    ];

    public function getCreatedAtAttribute($value){
        return date('d M Y - h:i A', strtotime($value));
    }

    public function getUpdatedAtAttribute($value){
        return date('d M Y - h:i A', strtotime($value));
    }
}
