<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberPayment extends Model
{
    protected $table = 'user_expences';

    protected $fillable = [
        'user_id',
        'admin_id',
        'amount',
        'ue_year',
        'status',
        'publish_status',
        'receipt',
    ];

    protected $hidden = [
        'admin_id',
        'status',
        'publish_status',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(SyndicateUser::class, 'user_id');
    }
}
