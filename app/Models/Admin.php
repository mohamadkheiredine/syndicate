<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
	use HasRoles, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
    	'first_name',
        'last_name',
        'email',
        'password',
        'blocked'
    ];

    protected $hidden = [
    	'password'
    ];

    protected $casts = [
        'blocked' => 'boolean'
    ];

    public function getFullNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }
}
