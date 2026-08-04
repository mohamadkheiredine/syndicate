<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active genders.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
