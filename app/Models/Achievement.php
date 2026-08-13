<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $table = 'syndicate_achievements';

    public $timestamps = false;

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
