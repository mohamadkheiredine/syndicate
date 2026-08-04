<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'image',
        'first_name',
        'last_name',
        'full_name',
        'name_code',
        'email',
        'country_code',
        'mobile_number',
        'pin',
        'verificationID',
        'new_user',
        'dob',
        'age',
        'password'
    ];

    protected $hidden = [
        'pin',
        'verificationID',
        'password',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'new_user' => 'boolean',
        'dob' => 'date',
    ];

    public function getImageAttribute($value){
        if(Config::get('services.s3bucket.status')){
            return $value ? Storage::disk('s3')->url(config('filesystems.disks.s3.bucket_name').'/users/'.$value) : null;
        } else {
            return $value ? asset(Storage::url('users/'.$value)) : null;
        }
    }

    public function getCreatedAtAttribute($value){
        return date('d M Y - h:i A', strtotime($value));
    }

    public function getUpdatedAtAttribute($value){
        return date('d M Y - h:i A', strtotime($value));
    }

    /*
    * RELATIONS
    */

    public function Addresses(){
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }
}
