<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class OurTeam extends Model
{
    protected $table = 'syndicate_ourteam';

    public $timestamps = false;

    protected $fillable = [
        'main_image',
        'short_description',
        'status',
        'publish_status',
    ];

    public function getMainImageAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('our-team/' . $value) : null;
    }

    public function getDisplayImageAttribute()
    {
        $raw = $this->getAttributes();

        return FilesHelper::getDisplayImageUrl('our-team', $raw['main_image'], $raw['publish_main_image'], $raw['publish_status']);
    }

    public function getDisplayDescriptionAttribute()
    {
        $raw = $this->getAttributes();

        if($raw['publish_short_description'] && (int) $raw['publish_status'] === 1){
            return $raw['publish_short_description'];
        }

        return $raw['short_description'];
    }
}
