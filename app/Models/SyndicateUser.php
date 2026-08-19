<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class SyndicateUser extends Authenticatable
{
    use SoftDeletes;

    protected $guard = 'web_user';

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
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Check a plaintext password against this user's stored hash. The old
     * CMS hashed every member's password with MD5 (all 1,279 imported rows
     * are 32-char MD5 hex), which is unsuitable for password storage - fast
     * to brute-force, no per-hash salt. New saves always use bcrypt via
     * setPasswordAttribute() above; this transparently upgrades a row still
     * on the legacy MD5 hash to bcrypt the moment its owner proves they
     * know the password, so the switch happens without resetting anyone.
     *
     */
    public function verifyPassword($plain)
    {
        $stored = $this->getAttributes()['password'];

        if(Hash::check($plain, $stored)){
            return true;
        }

        if(strlen($stored) === 32 && md5($plain) === $stored){
            $this->attributes['password'] = Hash::make($plain);
            $this->save();
            return true;
        }

        return false;
    }

    public function getPhotoAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('syndicate-users/' . $value) : null;
    }
}
