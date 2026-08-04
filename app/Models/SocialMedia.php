<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class SocialMedia extends Model
{
    protected $table = 'social_media';

    protected $fillable = [
        'id',
        'icon',
        'title',
        'link',
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

    public function getIconAttribute($value){
        if(Config::get('services.s3bucket.status')){
            return $value ? Storage::disk('s3')->url(config('filesystems.disks.s3.bucket_name').'/social-media/'.$value) : null;
        } else {
            return $value ? asset(Storage::url('social-media/'.$value)) : null;
        }
    }

    public function getCreatedAtAttribute($value){
        return date('d M Y - h:i A', strtotime($value));
    }

    public function getUpdatedAtAttribute($value){
        return date('d M Y - h:i A', strtotime($value));
    }
}
