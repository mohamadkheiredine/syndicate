<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Support extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contact_forms';

    protected $fillable = [
        'name',
        'email',
        'mobile_number',
        'support_category_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Get the support category associated with this contact form
     */
    public function supportCategory()
    {
        return $this->belongsTo(SupportCategory::class);
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read messages
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }
}
