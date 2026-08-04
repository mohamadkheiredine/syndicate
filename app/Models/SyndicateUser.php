<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SyndicateUser extends Model
{
    use SoftDeletes;

    protected $table = 'syndicate_user';

    protected $fillable = [
        'first_name',
        'fathers_name',
        'mothers_name',
        'last_name',
        'dob',
        'email',
        'mobile_number',
        'home_number',
        'password',
        'reset_code',
        'photo',
        'profile_link',
        'has_id',
        'facebook',
        'linkedin',
        'any_file',
        'lang',
        'status',
        'activation_code',
        'registration_date',
        'registration_fees',
        'kaza',
        'city',
        'street',
        'building',
        'floor',
        'company',
        'department',
        'unit',
        'date_employment',
        'blood_type',
    ];

    protected $hidden = [
        'password',
        'reset_code',
    ];

    // These legacy columns are NOT NULL with no DB default and aren't exposed in the
    // CMS form; default them so inserts don't fail under strict SQL mode.
    protected $attributes = [
        'activation_code' => '',
        'mothers_name' => '',
        'home_number' => '',
        'reset_code' => '',
        'profile_link' => '',
        'facebook' => '',
        'linkedin' => '',
        'any_file' => '',
        'lang' => '',
    ];

    protected $casts = [
        'has_id' => 'boolean',
        'dob' => 'date',
        'registration_date' => 'datetime',
        'date_employment' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = md5($value);
    }

    public function getPhotoAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('syndicate-users/' . $value) : null;
    }
}
