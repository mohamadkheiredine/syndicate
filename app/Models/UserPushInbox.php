<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPushInbox extends Model
{
	protected $table = "user_push_inbox";

	protected $fillable = [
		'user_id',
		'push_inbox_id'
	];
}
