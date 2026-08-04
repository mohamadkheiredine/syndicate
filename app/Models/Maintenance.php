<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class Maintenance extends Model
{
    protected $table = 'maintenance';

	protected $fillable = [
        'maintenance_mode',
        'image',
        'title',
        'text',
        'secret'
    ];

    public function getImageAttribute($value){
        if(Config::get('services.s3bucket.status')){
            return $value ? Storage::disk('s3')->url(config('filesystems.disks.s3.bucket_name').'/maintenance/'.$value) : null;
        } else {
            return $value ? asset(Storage::url('maintenance/'.$value)) : null;
        }
    }
}
