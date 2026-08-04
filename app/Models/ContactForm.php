<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ContactForm extends Model
{
    protected $table = 'contact_form';

    protected $fillable = [
        'full_name',
        'support_category_id',
        'message'
    ];

    protected $with = [
        'SupportCategory'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function(Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }

    public function SupportCategory(){
        return $this->belongsTo('App\Models\SupportCategory', 'support_category_id');
    }
}
