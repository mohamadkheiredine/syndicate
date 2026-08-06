<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class PushInbox extends Model
{
	protected $table = "push_inbox";

	protected $fillable = [
		'user_id',
		'subject',
		'message',
		'image',
		'type'
	];

	public $translatable = [
        'subject',
        'message'
    ];

	protected $casts = [
        'created_at'  => 'date:M d Y'
    ];

	public function getImageAttribute($value){
		return $value ? FilesHelper::getImageFullUrl('push-notifications/' . $value) : null;
	}

	/*
    * RELATIONS
    */

	public function PushRead(){
		return $this->hasMany(UserPushInbox::class, 'push_inbox_id', 'id');
	}
}
