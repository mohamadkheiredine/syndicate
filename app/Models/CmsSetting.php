<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class CmsSetting extends Model
{
	protected $fillable = [
        'logo',
        'primary_color'
    ];

    public function getLogoAttribute($value){
        if(Config::get('services.s3bucket.status')){
            return $value ? Storage::disk('s3')->url(config('filesystems.disks.s3.bucket_name').'/cms-settings/'.$value) : null;
        } else {
            return $value ? asset(Storage::url('cms-settings/'.$value)) : null;
        }
    }
}
