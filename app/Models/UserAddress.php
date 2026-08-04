<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'label',
        'country_code',
        'mobile_number',
        'city',
        'street',
        'building',
        'apartment',
        'instructions',
        'lat',
        'lng',
        'google_map',
        'is_default'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_default' => 'boolean',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    /**
     * Get the user that owns the address
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
