<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'id',
        'phone',
        'code',
        'name',
        'currency',
        'publish'
    ];

    protected $hidden = [
        'publish',
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'flag'
    ];

    public function scopeGeneralScope($query){
        return $query->wherePublish(1);
    }

    public function getFlagAttribute(){
        return asset('/assets-web/images/flags/'.$this->code.'.png');
    }

    public function getCreatedAtAttribute($value){
        return date('d M Y - h:i A', strtotime($value));
    }

    public function getUpdatedAtAttribute($value){
        return date('d M Y - h:i A', strtotime($value));
    }
}
