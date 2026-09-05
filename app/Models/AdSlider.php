<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class AdSlider extends Model
{
    protected $table = 'syndicate_ad_sliders';

    public $timestamps = false;

    protected $fillable = [
        'main_image',
        'text',
        'status',
        'publish_status',
    ];

    // Legacy shadow columns - the new CMS never writes to these (see
    // AdSliderController), only the real fields above. NOT NULL with no DB
    // default, so they still need a value on insert. "title" and
    // "subtitle" are dead columns from the old CMS - fully commented out in
    // both its create and list admin pages - so they are not fillable
    // either. This table has no admin_id column, same as Home
    // Sliders/Achievements.
    protected $attributes = [
        'title' => '',
        'publish_title' => '',
        'subtitle' => '',
        'publish_subtitle' => '',
        'publish_text' => '',
    ];

    public function getMainImageAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('ad-slider/' . $value) : null;
    }

    public function getDisplayImageAttribute()
    {
        $raw = $this->getAttributes();

        return FilesHelper::getDisplayImageUrl('ad-slider', $raw['main_image'], $raw['publish_main_image'] ?? null, $raw['publish_status'], 'publish_ad_slider');
    }
}
