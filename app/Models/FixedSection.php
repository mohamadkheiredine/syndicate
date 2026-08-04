<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class FixedSection extends Model
{
    protected $fillable = [
        'slug',
        'image',
        'title',
        'text'
    ];

    protected $hidden = [
        'id',
        'slug',
        'created_at',
        'updated_at'
    ];

    public function getImageAttribute($value){
        if(Config::get('services.s3bucket.status')){
            return $value ? Storage::disk('s3')->url(config('filesystems.disks.s3.bucket_name').'/fixed-sections/'.$value) : null;
        } else {
            return $value ? asset(Storage::url('fixed-sections/'.$value)) : null;
        }
    }

    public function getCreatedAtAttribute($value){
        return date('d M Y - h:i A', strtotime($value));
    }

    public function getUpdatedAtAttribute($value){
        return date('d M Y - h:i A', strtotime($value));
    }
}
