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
        return $value ? FilesHelper::getImageFullUrl('our_team/' . $value) : null;
    }

    // The new CMS edits a single image + description (no separate
    // draft/published copies), so the website reads those directly.
    // The old publish_main_image / publish_short_description columns are
    // stale import data the new CMS never updates - ignored here.
    public function getDisplayImageAttribute()
    {
        return $this->main_image;
    }

    public function getDisplayDescriptionAttribute()
    {
        return $this->getAttributes()['short_description'] ?? '';
    }
}
