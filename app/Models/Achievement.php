<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $table = 'syndicate_achievements';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'number',
        'status',
        'publish_status',
    ];

    // Legacy shadow column - the new CMS never writes to this (see
    // AchievementController), only the real title field above. NOT NULL
    // with no DB default, so it still needs a value on insert. This table
    // has no admin_id column, same as Home Sliders.
    protected $attributes = [
        'publish_title' => '',
    ];

    public function getDisplayNumberAttribute()
    {
        $raw = $this->getAttributes();

        if($raw['publish_number'] !== null && $raw['publish_number'] !== '' && (int) $raw['publish_status'] === 1){
            return $raw['publish_number'];
        }

        return $raw['number'];
    }

    public function getDisplayTitleAttribute()
    {
        $raw = $this->getAttributes();

        if($raw['publish_title'] && (int) $raw['publish_status'] === 1){
            return $raw['publish_title'];
        }

        return $raw['title'];
    }
}
