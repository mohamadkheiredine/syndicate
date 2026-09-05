<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class SyndicateUser extends Authenticatable
{
    use SoftDeletes, HasApiTokens;

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

    // Matches old's real Users model $hidden list exactly (App\Models\Api\Users
    // in syndicate-website-master) - old hides far more than just the
    // password/reset_code this project originally hid, so a raw toArray()
    // response (e.g. syndicateLogin) was leaking fields old never returns.
    protected $hidden = [
        'updated_at',
        'password',
        'reset_code',
        'status',
        'activation_code',
        'fathers_name',
        'mothers_name',
        'dob',
        'home_number',
        'company',
        'any_file',
        'linkedin',
        'facebook',
        'lang',
        'profile_link',
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

    // Matches the old CMS exactly - plain MD5, no salt.
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = md5($value);
    }

    public function verifyPassword($plain)
    {
        return md5($plain) === $this->getAttributes()['password'];
    }

    // These plain string columns genuinely store NULL for a real chunk of
    // imported rows (579 confirmed, e.g. members imported without an
    // address on file) rather than ''. Old's real API response shows
    // these as empty strings, not null, for the same rows - coercing on
    // read here rather than back-filling the imported data, since old's
    // own DB dump isn't reachable to confirm/fix at the source.
    public function getKazaAttribute($value) { return $value ?? ''; }
    public function getCityAttribute($value) { return $value ?? ''; }
    public function getStreetAttribute($value) { return $value ?? ''; }
    public function getBuildingAttribute($value) { return $value ?? ''; }
    public function getFloorAttribute($value) { return $value ?? ''; }
    public function getDepartmentAttribute($value) { return $value ?? ''; }
    public function getUnitAttribute($value) { return $value ?? ''; }

    public function getPhotoAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('user/' . $value) : null;
    }

    // Missing before - the profile page's blade template already expected
    // $user->any_file to resolve to a full URL.
    public function getAnyFileAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('upload_file/' . $value) : null;
    }

    public function pushTokens()
    {
        return $this->hasMany(UserPush::class, 'users_id');
    }
}
