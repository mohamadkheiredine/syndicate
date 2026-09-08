<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyndicateAbout extends Model
{
    protected $table = 'syndicate_about';

    public $timestamps = false;

    protected $fillable = [
        'description',
        'publish_status',
    ];


    protected $attributes = [
        'publish_description' => '',
    ];

    public function getDescriptionAttribute($value)
    {
        return stripslashes(html_entity_decode($value ?? ''));
    }
    public function getDisplayDescriptionAttribute()
    {
        return $this->description;
    }
}
