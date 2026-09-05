<?php

namespace App\Models;

use App\Helpers\FilesHelper;
use Illuminate\Database\Eloquent\Model;

class SplashSlider extends Model
{
    protected $table = 'syndicate_splash_sliders';

    public $timestamps = false;

    protected $fillable = [
        'main_image',
        'title',
        'subtitle',
        'status',
        'publish_status',
    ];

    // Legacy shadow columns - the new CMS never writes to these (see
    // SplashSliderController), only the real fields above. NOT NULL with no
    // DB default, so they still need a value on insert. "text" is a dead
    // column from the old CMS - fully commented out in both its create and
    // list admin pages - so it is not fillable either. This table has no
    // admin_id column, same as Home Sliders/Achievements.
    protected $attributes = [
        'publish_title' => '',
        'publish_subtitle' => '',
        'text' => '',
        'publish_text' => '',
    ];

    public function getMainImageAttribute($value)
    {
        return $value ? FilesHelper::getImageFullUrl('splash-slider/' . $value) : null;
    }

    public function getDisplayImageAttribute()
    {
        $raw = $this->getAttributes();

        return FilesHelper::getDisplayImageUrl('splash-slider', $raw['main_image'], $raw['publish_main_image'] ?? null, $raw['publish_status'], 'publish_splash_slider');
    }
}
