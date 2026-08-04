<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Faq extends Model
{
    protected $fillable = [
        'id',
        'title',
        'text',
        'pos',
        'publish'
    ];

    protected $hidden = [
        'id',
        'pos',
        'publish',
        'created_at',
        'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder) {
            $builder->orderBy('pos');
        });
    }

    public function scopeGeneralScope($query){
        return $query->wherePublish(1);
    }

    public function getCreatedAtAttribute($value){
        return date('d M Y - h:i A', strtotime($value));
    }

    public function getUpdatedAtAttribute($value){
        return date('d M Y - h:i A', strtotime($value));
    }
}
